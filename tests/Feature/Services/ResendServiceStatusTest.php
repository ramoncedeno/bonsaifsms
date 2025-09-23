<?php

namespace Tests\Feature\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ResendServiceStatusTest extends TestCase
{
    /**
     * Test that the Resend API is reachable.
     *
     * @return void
     */
    public function test_resend_api_is_reachable()
    {
        $logChannel = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/test_result.log'),
        ]);

        try {
            $response = Http::get('https://api.resend.com');

            if ($response->ok() || $response->clientError() || $response->serverError()) {
                $message = 'Test passed: Resend API is reachable. Status: ' . $response->status();
                $logChannel->info($message);
                $this->assertTrue(true); // Test passes if we get any response
            } else {
                $message = 'Test failed: Resend API is unreachable. No response.';
                $logChannel->error($message);
                $this->fail($message);
            }
        } catch (\Exception $e) {
            $message = 'Test failed: Failed to connect to Resend API: ' . $e->getMessage();
            $logChannel->error($message);
            $this->fail($message);
        }
    }
}
