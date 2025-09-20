<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;


test('email verification screen can be rendered', function () {

     try {

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);

         Log::channel('test_result')->info('Test passed: email verification screen can be rendered');

     } catch (\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        );

        throw $e; // allows phpunit to mark the test as failed
    }


});





test('email can be verified', function () {

    try {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
        $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');

        Log::channel('test_result')->info('Test passed: email can be verified');

     } catch (\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        );

        throw $e; // allows phpunit to mark the test as failed
    }


});

test('email is not verified with invalid hash', function () {

    try {    $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        expect($user->fresh()->hasVerifiedEmail())->toBeFalse();


        Log::channel('test_result')->info('Test passed: email is not verified with invalid hash');

    } catch (\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        );

        throw $e; // allows phpunit to mark the test as failed
    }


});
