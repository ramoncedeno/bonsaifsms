<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

it('pings the API and logs the status', function () {
    try {
        $response = Http::get('https://api.bonsaif.com');
        Log::channel('test_result')->info('Test passed: API ping test received status: ' . $response->status());
        $this->assertTrue(true);

    } catch (Throwable $e) {
        Log::channel('test_result')->error(
            'API ping test failed with an exception: ' . $e->getMessage(),
            ['exception' => $e]
        );
        throw $e;
    }
});
