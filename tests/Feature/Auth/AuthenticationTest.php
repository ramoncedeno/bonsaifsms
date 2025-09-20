<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Volt\Volt;

test('login screen can be rendered', function () {
    try {
        $response = $this->get('/login');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.login');
        Log::channel('test_result')->info('Test passed: login screen can be rendered');

    } catch (\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        );

        throw $e; //allowsPhpunitToMarkTheTestAsFailed
    }
});

test('users can authenticate using the login screen', function () {
    try {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
        Log::channel('test_result')->info('Test passed: users can authenticate using the login screen');
    } catch (\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
);

        throw $e; // allows phpunit to mark the test as failed
    }
});

test('users can not authenticate with invalid password', function () {
    try {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'wrong-password');

        $component->call('login');

        $component
            ->assertHasErrors()
            ->assertNoRedirect();

        $this->assertGuest();
        Log::channel('test_result')->info('Test passed: users can not authenticate with invalid password');
    } catch (\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace
);

        throw $e; // allows phpunit to mark the test as failed
    }
});

test('navigation menu can be rendered', function () {
    try {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response
            ->assertOk()
            ->assertSeeVolt('layout.navigation');
        Log::channel('test_result')->info('Test passed: navigation menu can be rendered');
    } catch (\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
);

        throw $e; // allows phpunit to mark the test as failed
    }
});

test('users can logout', function () {
    try {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('layout.navigation');

        $component->call('logout');

        $component
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        Log::channel('test_result')->info('Test passed: users can logout');
    } catch (\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        );

        throw $e; // allows phpunit to mark the test as failed
    }
});
