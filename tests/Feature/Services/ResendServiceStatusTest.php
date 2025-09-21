<?php

namespace Tests\Feature\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ResendServiceStatusTest extends TestCase
{
    /**
     * Simply checks the Resend service status and logs the result.
     *
     * @return void
     */
    public function test_resend_service_connection(): void
    {
        try {
            // 1. Attempt to send an email. Laravel will automatically use the configuration
            //    loaded from the testing environment (.env.testing).
            Mail::raw('Service connectivity test.', function ($message) {
                $message->to('rcedeno@igroupsolution.con')
                        ->subject('Connectivity Test');
            });

            // 2. If the line above didn't throw an exception, the connection was successful.
            Log::channel('test_result')->info(
                'Resend test successful. The connection to the service is working correctly.'
            );

            // Assert that the test passed.
            $this->assertTrue(true);

        } catch (\Throwable $e) {
            // 3. If anything failed (e.g., wrong API key), the error is caught.
            Log::channel('test_result')->error(
                'Resend test failed: ' . $e->getMessage(),
                ['exception' => $e] // Logs the full exception details.
            );

            // 4. Re-throw the exception so PHPUnit marks the test as failed.
            throw $e;
        }
    }
}
