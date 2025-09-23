<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ViewAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider viewRoutesProvider
     */
    public function test_authenticated_user_can_access_view_routes(string $route): void
    {
        // Seed the roles and permissions
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('admin');

        try {
            $response = $this->actingAs($user)->get($route);
            $response->assertOk();

            Log::channel('test_result')->info('Test passed: The route \'' . $route . '\' is accessible');

            // We still want a positive assertion for PHPUnit's output
            $this->assertTrue(true);

        } catch (\Throwable $e) {
            Log::channel('test_result')->error(
                'Test failed: The route \'' . $route . '\' is not accessible. ' . $e->getMessage(),
                ['exception' => $e]
            );
            // Re-throw the exception to ensure PHPUnit marks the test as failed
            throw $e;
        }
    }

    public static function viewRoutesProvider(): array
    {
        return [
            ['/dashboard'],
            ['/profile'],
            ['/sms/view'],
            ['/users'],
        ];
    }
}
