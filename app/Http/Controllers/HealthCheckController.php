<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Imports\SmsImport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class HealthCheckController extends Controller
{
    /**
     * Performs a series of health checks on various application services.
     *
     * @return JsonResponse
     */
    public function check(): JsonResponse
    {
        try {
            $services = [
                'database' => [$this, 'checkDatabase'],
                'cache' => [$this, 'checkCache'],
                'filesystem' => [$this, 'checkFilesystem'],
                'environment' => [$this, 'checkEnvironment'],
                'app_key' => [$this, 'checkAppKey'],
                'routes' => [$this, 'checkAccessibleRoutes'],
                'controllers' => [$this, 'checkControllers'],
                'livewire_components' => [$this, 'checkLivewireComponents'],
                'excel_library' => [$this, 'checkExcelLibrary'],
                'permissions_package' => [$this, 'checkPermissionsPackage'],
                'mail_service' => [$this, 'checkMailService'],
                'bonsaif_api' => [$this, 'checkBonsaifApi'],
                'resend_api' => [$this, 'checkResendApi'],
            ];

            // preserve service keys so that the JSON identifies each check
            $checks = collect($services)
                ->mapWithKeys(fn(callable $closure, $key) => [$key => $this->executeCheck($closure)])
                ->all();

            // detect if any check returned ERROR
            $hasErrors = collect($checks)->contains(fn($c) => isset($c['status']) && $c['status'] === 'ERROR');
            $status = $hasErrors ? 503 : 200;

            $response = response()->json([
                'status' => $hasErrors ? 'ERROR' : 'OK',
                'timestamp' => now()->toIso8601String(),
                'checks' => $checks,
            ], $status);

            Log::channel('healthcheck_result')->info('Health check performed.', ['response' => $response->getData(true)]);

            return $response;
        } catch (Exception $e) {
            Log::channel('healthcheck_result')->error('An unexpected error occurred during health check.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'ERROR',
                'message' => 'An unexpected error occurred.',
                'timestamp' => now()->toIso8601String(),
            ], 500);
        }
    }

    /**
     * Executes a given health check closure and measures its execution time.
     *
     * @param callable $closure The health check function to execute.
     * @return array The result of the health check, including status, message, duration, and status code.
     */
    private function executeCheck(callable $closure): array
    {
        $startTime = microtime(true);
        $caughtException = null;

        try {
            $result = $closure();
        } catch (Exception $e) {
            $caughtException = $e;
            $result = ['status' => 'ERROR', 'message' => $e->getMessage()];
        }
        $endTime = microtime(true);

        $duration = round(($endTime - $startTime) * 1000, 2);
        $statusCode = $result['statusCode'] ?? (
            (isset($result['status']) && $result['status'] === 'ERROR')
                ? ($caughtException?->getCode() ?: 503)
                : 200
        );

        return array_merge($result, [
            'duration_ms' => $duration,
            'statusCode' => $statusCode,
        ]);
    }

    /**
     * Checks the database connection.
     *
     * @return array
     * @throws \Exception If the database connection fails.
     */
    private function checkDatabase(): array
    {
        DB::connection()->getPdo();
        return ['status' => 'OK', 'message' => 'Database connection successful.'];
    }

    /**
     * Checks if the cache is working correctly by storing and retrieving a value.
     *
     * @return array
     * @throws \Exception If storing or retrieving from cache fails.
     */
    private function checkCache(): array
    {
        $key = 'health_check_' . now()->timestamp;
        Cache::put($key, 'ok', 10);
        $value = Cache::pull($key);

        if ($value !== 'ok') {
            throw new Exception('Failed to retrieve value from cache.');
        }
        return ['status' => 'OK', 'message' => 'Cache is working.'];
    }

    /**
     * Checks if the application's filesystem is writable.
     *
     * @return array
     * @throws \Exception If the filesystem is not writable.
     */
    private function checkFilesystem(): array
    {
        $filename = 'health_check_' . now()->timestamp . '.txt';
        Storage::put($filename, 'ok');

        if (Storage::exists($filename)) {
            Storage::delete($filename);
            return ['status' => 'OK', 'message' => 'Filesystem is writable.'];
        }

        throw new Exception('Filesystem is not writable.');
    }

    /**
     * Checks the current application environment.
     *
     * @return array
     */
    private function checkEnvironment(): array
    {
        $env = app()->environment();

        return $env === 'production'
            ? ['status' => 'OK', 'message' => 'Application is in production environment.']
            : ['status' => 'WARNING', 'message' => "Application is not in production environment (current: {$env})."];
    }

    /**
     * Checks if the application key is set.
     *
     * @return array
     * @throws \Exception If the application key is not set.
     */
    private function checkAppKey(): array
    {
        if (!config('app.key')) {
            throw new Exception('Application key is not set.');
        }
        return ['status' => 'OK', 'message' => 'Application key is set.'];
    }

    /**
     * Checks if key application routes are accessible.
     *
     * @return array
     * @throws \Exception If any of the routes are not accessible.
     */
    private function checkAccessibleRoutes(): array
    {
        try {
            $testUser = User::first();

            if (!$testUser) {
                return [
                    'status' => 'SKIPPED',
                    'message' => 'No users found in the database. Cannot check authenticated routes.'
                ];
            }

            if (!$testUser->hasRole('admin')) {
                return [
                    'status' => 'SKIPPED',
                    'message' => 'The first user is not an admin. Cannot check authenticated routes. Please run database seeders.'
                ];
            }
        } catch (RoleDoesNotExist $e) {
            return [
                'status' => 'SKIPPED',
                'message' => 'The "admin" role does not exist. Cannot check authenticated routes. Please run database seeders.'
            ];
        }

        Auth::login($testUser);

        $routes = ['/dashboard', '/profile', '/sms/view', '/users', '/sms-consumption'];
        $results = [];
        $allOk = true;

        foreach ($routes as $route) {
            $request = Request::create($route, 'GET');
            $response = app()->handle($request);
            $status = $response->getStatusCode();
            $results[$route] = $status;

            if ($status !== 200) {
                $allOk = false;
            }
        }

        // Auth::logout(); // Removed to prevent logging out the current user

        if (!$allOk) {
            throw new Exception(json_encode($results));
        }

        return [
            'status' => 'OK',
            'message' => json_encode($results)
        ];
    }

    /**
     * Checks for the presence of key application controllers.
     *
     * @return array
     * @throws \Exception If any key controllers are missing.
     */
    private function checkControllers(): array
    {
        $controllers = [
            'DashboardController.php',
            'SmsImportController.php',
            'SmsTransactionController.php',
            'UserController.php',
            'SmsSenderController.php',
        ];

        $missing = collect($controllers)
            ->filter(fn($controller) => !file_exists(app_path("Http/Controllers/{$controller}")))
            ->values()
            ->all();

        if (!empty($missing)) {
            throw new Exception('Missing key controllers: ' . implode(', ', $missing));
        }
        return ['status' => 'OK', 'message' => 'All key controllers are present.'];
    }

    /**
     * Checks for the presence of key Livewire components.
     *
     * @return array
     * @throws \Exception If any key Livewire components are missing.
     */
    private function checkLivewireComponents(): array
    {
        $components = [
            'SmsAttemptView.php',
            'SmsConsumptionDashboard.php',
            'SmsSummary.php',
            'UserManagement.php',
            'Actions/Logout.php',
            'Forms/LoginForm.php',
        ];

        $missing = collect($components)
            ->filter(fn($component) => !file_exists(app_path("Livewire/{$component}")))
            ->values()
            ->all();

        if (!empty($missing)) {
            throw new Exception('Missing key Livewire components: ' . implode(', ', $missing));
        }
        return ['status' => 'OK', 'message' => 'All key Livewire components are present.'];
    }



    /**
     * Checks for the presence of key import files.
     *
     * @return array
     * @throws \Exception If any key import files are missing.
     */
    private function checkImports(): array
    {
        $imports = [
            'SmsImport.php',
            'UsersImport.php',
        ];

        $missing = collect($imports)
            ->filter(fn($import) => !file_exists(app_path("Imports/{$import}")))
            ->values()
            ->all();

        if (!empty($missing)) {
            throw new Exception('Missing key import files: ' . implode(', ', $missing));
        }
        return ['status' => 'OK', 'message' => 'All key import files are present.'];
    }

    /**
     * Checks if the Maatwebsite/Excel library is available.
     *
     * @return array
     * @throws \Exception If the Excel library is not available.
     */
    private function checkExcelLibrary(): array
    {
        if (!class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            throw new Exception('Maatwebsite/Excel library is not available.');
        }
        return ['status' => 'OK', 'message' => 'Maatwebsite/Excel library is available.'];
    }

    /**
     * Checks if the Spatie/laravel-permission package models are available.
     *
     * @return array
     * @throws \Exception If the permission package models are not available.
     */
    private function checkPermissionsPackage(): array
    {
        $available = class_exists(\Spatie\Permission\Models\Role::class)
            && class_exists(\Spatie\Permission\Models\Permission::class);

        if (!$available) {
            throw new Exception('Spatie/laravel-permission models are not available.');
        }
        return ['status' => 'OK', 'message' => 'Spatie/laravel-permission package is available.'];
    }

    /**
     * Checks if the mail service is configured.
     *
     * @return array
     * @throws \Exception If the mail service is not configured.
     */
    private function checkMailService(): array
    {
        Mail::mailer();
        return ['status' => 'OK', 'message' => 'Mail service is configured.'];
    }

    /**
     * Checks connectivity to the Bonsaif API.
     *
     * @return array
     * @throws \Exception If the Bonsaif API is unreachable.
     */
    private function checkBonsaifApi(): array
    {
        $response = Http::timeout(5)
            ->get('https://api.bonsaif.com');

        return [
            'status' => 'SKIPPED',
            'message' => 'Bonsaif API check skipped. Status: ' . $response->status(),
            'statusCode' => $response->status()
        ];
    }

    /**
     * Checks connectivity to the Resend API.
     *
     * @return array
     * @throws \Exception If the Resend API is unreachable.
     */
    private function checkResendApi(): array
    {
        $response = Http::timeout(5)->get('https://api.resend.com');

        if (!$response->successful()) {
            throw new Exception('Resend API is unreachable. Status: ' . $response->status(), $response->status());
        }
        return ['status' => 'OK', 'message' => 'Resend API is reachable.'];
    }
}
