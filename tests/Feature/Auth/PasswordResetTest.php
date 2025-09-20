<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Livewire\Volt\Volt;




    test('reset password link screen can be rendered', function () {

    try{

        $response = $this->get('/forgot-password');

        $response
            ->assertSeeVolt('pages.auth.forgot-password')
            ->assertStatus(200);

        Log::channel('test_result')->info('Test passed: reset password link screen can be rendered');

    }catch(\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        ); throw $e; //allows Phpunit To Mark The TestAs Failed

    }
});




test('reset password link can be requested', function () {

    try{

        Notification::fake();

        $user = User::factory()->create();

        Volt::test('pages.auth.forgot-password')
            ->set('email', $user->email)
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPassword::class);

        Log::channel('test_result')->info('Test passed: reset password link can be requested');

    }catch(\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        ); throw $e; //allows Phpunit To Mark The TestAs Failed

    }

});

test('reset password screen can be rendered', function () {
    try{

        Notification::fake();

        $user = User::factory()->create();

        Volt::test('pages.auth.forgot-password')
            ->set('email', $user->email)
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/'.$notification->token);


            $response
                ->assertSeeVolt('pages.auth.reset-password')
                ->assertStatus(200);

                Log::channel('test_result')->info('Test passed: reset password screen can be rendered');

            return true;


        });


    }catch(\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        ); throw $e; //allows Phpunit To Mark The TestAs Failed

    }

});

test('password can be reset with valid token', function () {
    try{

        Notification::fake();

        $user = User::factory()->create();

        Volt::test('pages.auth.forgot-password')
            ->set('email', $user->email)
            ->call('sendPasswordResetLink');
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $component = Volt::test('pages.auth.reset-password', ['token' => $notification->token])
                ->set('email', $user->email)
                ->set('password', 'password')
                ->set('password_confirmation', 'password');

            $component->call('resetPassword');

            $component
                ->assertRedirect('/login')
                ->assertHasNoErrors();

                Log::channel('test_result')->info('Test passed: password can be reset with valid token');

            return true;


        });


    }catch(\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        ); throw $e; //allows Phpunit To Mark The TestAs Failed

    }


});
