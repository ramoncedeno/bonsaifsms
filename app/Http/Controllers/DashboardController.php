<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    public function index()
    {
        $logFilePath = storage_path('logs/test_result.log');
        $successCount = 0;
        $failureCount = 0;

        if (File::exists($logFilePath)) {
            $logContent = File::get($logFilePath);
            $lines = explode("\n", $logContent);

            foreach ($lines as $line) {
                if (str_contains($line, 'Test passed:')) {
                    $successCount++;
                } elseif (str_contains($line, 'Test failed:')) {
                    $failureCount++;
                }
            }
        }

        $totalTests = $successCount + $failureCount;
        $successPercentage = $totalTests > 0 ? round(($successCount / $totalTests) * 100, 2) : 0;

        return view('dashboard', compact('successPercentage', 'successCount', 'failureCount'));
    }
}
