<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BonsaifSmsServiceTest extends TestCase
{
    /**
     * A basic feature test to check the BONSAIF SMS service.
     *
     * This test sends a real request to the BONSAIF SMS URL endpoint
     * to confirm it's responsive. The full response is then appended
     * to storage/logs/test_result.log using a dedicated logging channel.
     *
     * This test intentionally does NOT interact with the database (e.g., sms_transaction model)
     * to avoid data modification.
     *
     * @return void
     */
    public function test_bonsaif_sms_service_responds_and_logs_result()
    {
   
        try {
            // --- Configuration ---
            // Ensure your .env.testing or phpunit.xml file has the necessary environment variables.
            $url = env('BONSAIF_SMS_URL');
            $key = env('BONSAIF_SMS_KEY');
            $auth = env('BONSAIF_AUTH');

            // Check if essential configuration is missing to prevent test failure.
            if (!$url || !$key || !$auth) {
                $this->markTestSkipped('SMS service environment variables are not set.');
            }

            // --- Test Data ---
            // We use dummy data for the test request.
            $testPhone = '5576680093'; // A valid 10-digit phone number format for the request
            $testMessage = 'This is a connectivity test.';

            // --- Execute Request ---
            // Perform the actual HTTP request to the external service
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $auth
            ])->get($url, [
                'phone' => $testPhone,
                'message' => $testMessage,
                'out' => 'json',
                'key' => $key,
            ]);

            // --- Log Successful Response ---
            $context = [
                'endpoint' => $url,
                'status_code' => $response->status(),
                'response_body' => $response->json() ?? $response->body(), // Log parsed JSON or raw body
            ];
            Log::channel('test_result')->info('Test passed: SMS Service responded successfully.', $context);

            // --- Assertions ---
            $this->assertTrue($response->successful(), 'The request to the SMS service was not successful.');
            $this->assertIsArray($response->json(), 'The response from the SMS service was not valid JSON.');

        } catch (\Throwable $e) {
            // --- Log Exception ---
            Log::channel('test_result')->error(
                'Test failed: An exception occurred during the test: ' . $e->getMessage(),
                ['exception' => $e] // This will log the full stack trace.
            );
            // Re-throw the exception to allow PHPUnit to correctly mark the test as failed.
            throw $e;
        }
    }
}

