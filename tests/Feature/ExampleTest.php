<?php

use Illuminate\Support\Facades\Log;



    it('returns a successful response', function () {

         try{

            $response = $this->get('/login');

            $response->assertStatus(200);

            Log::channel('test_result')->info('Test passed: returns a successful response');


        }catch(\Throwable $e) {
            Log::channel('test_result')->error(
                'Test failed: ' . $e->getMessage(),
                ['exception' => $e] // <- This makes the stacktrace register
            ); throw $e; //allows Phpunit To Mark The TestAs Failed
        }
    });


