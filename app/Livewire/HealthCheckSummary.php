<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log; // For logging errors during parsing
use Carbon\Carbon; // To parse timestamps if needed for filtering/grouping by date
use Illuminate\Support\Facades\Http; // Added for making HTTP requests

class HealthCheckSummary extends Component
{
    public $statusCounts = [];
    public $totalChecks = 0;
    public $lastCheckTime = null;
    public $appLifePercentage = 0; // New property

    public function mount()
    {
        // Trigger the health check when the component mounts
        try {
            Http::get(route('healthcheck.check'));
        } catch (\Exception $e) {
            Log::error("Failed to trigger health check on mount: " . $e->getMessage());
            // Optionally, emit a browser event to show an error message
            $this->dispatch('notify', ['message' => 'Failed to run health check automatically.', 'type' => 'error']);
        }

        $logFilePath = storage_path('logs/healthcheck_result.log');
        $statusTallies = [];
        $latestTimestamp = null;

        if (File::exists($logFilePath)) {
            $logContent = File::get($logFilePath);
            $lines = explode("\n", $logContent);

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) {
                    continue;
                }

                // Extract JSON part from the log line
                // The JSON part starts after "Health check performed. "
                $jsonString = substr($line, strpos($line, '{'));

                if ($jsonString) {
                    try {
                        $data = json_decode($jsonString, true);

                        if (json_last_error() === JSON_ERROR_NONE && isset($data['response']['status'])) {
                            $status = $data['response']['status'];
                            $statusTallies[$status] = ($statusTallies[$status] ?? 0) + 1;
                            $this->totalChecks++;

                            // Update latest check time
                            if (isset($data['response']['timestamp'])) {
                                $currentTimestamp = Carbon::parse($data['response']['timestamp']);
                                if ($latestTimestamp === null || $currentTimestamp->greaterThan($latestTimestamp)) {
                                    $latestTimestamp = $currentTimestamp;
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("Error parsing health check log line: " . $e->getMessage() . " - Line: " . $line);
                    }
                }
            }
        }

        $this->statusCounts = $statusTallies;
        $this->lastCheckTime = $latestTimestamp ? $latestTimestamp->format('Y-m-d') : 'N/A';

        // Calculate App Life Percentage
        $successfulChecks = $this->statusCounts['OK'] ?? 0;
        if ($this->totalChecks > 0) {
            $this->appLifePercentage = round(($successfulChecks / $this->totalChecks) * 100, 2);
        } else {
            $this->appLifePercentage = 0;
        }
    }

    public function render()
    {
        return view('livewire.health-check-summary');
    }
}
