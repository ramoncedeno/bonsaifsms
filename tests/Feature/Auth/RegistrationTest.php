<?php

namespace Tests\Feature\Auth;

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Log;

test('registration screen can be rendered', function () {

     try {

        $response = $this->get('/register');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.register');

        Log::channel('test_result')->info('Test passed: registration screen can be rendered');

     }catch (\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        );

        throw $e; // allows phpunit to mark the test as failed
    }


});

test('new users can register', function () {
    try {
        $component = Volt::test('pages.auth.register')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('register');

        $component->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();

        Log::channel('test_result')->info('Test passed: new users can register');
    } catch (\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        );

        throw $e; // allows phpunit to mark the test as failed
    }
});
