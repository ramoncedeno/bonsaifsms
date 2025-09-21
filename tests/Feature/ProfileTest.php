<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Volt\Volt;

test('profile page is displayed', function () {

    try{
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/profile');

        $response
            ->assertOk()
            ->assertSeeVolt('profile.update-profile-information-form')
            ->assertSeeVolt('profile.update-password-form')
            ->assertSeeVolt('profile.delete-user-form');

        Log::channel('test_result')->info('Test passed: profile page is displayed');


    }catch(\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        ); throw $e; //allows Phpunit To Mark The TestAs Failed
    }

});



test('profile information can be updated', function () {

    try{
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('profile.update-profile-information-form')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->call('updateProfileInformation');

        $component
            ->assertHasNoErrors()
            ->assertNoRedirect();

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);

        Log::channel('test_result')->info('Test passed: profile information can be updated');

    }catch(\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        ); throw $e; //allows Phpunit To Mark The TestAs Failed
    }
});

test('email verification status is unchanged when the email address is unchanged', function () {

    try{
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('profile.update-profile-information-form')
            ->set('name', 'Test User')
            ->set('email', $user->email)
            ->call('updateProfileInformation');

        $component
            ->assertHasNoErrors()
            ->assertNoRedirect();

        $this->assertNotNull($user->refresh()->email_verified_at);

        Log::channel('test_result')->info('Test passed: email verification status is unchanged when the email address is unchanged');


    }catch(\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        ); throw $e; //allows Phpunit To Mark The TestAs Failed
    }
});

test('user can delete their account', function () {

    try{
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('profile.delete-user-form')
            ->set('password', 'password')
            ->call('deleteUser');

        $component
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());

        Log::channel('test_result')->info('Test passed: user can delete their account');


    }catch(\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        ); throw $e; //allows Phpunit To Mark The TestAs Failed
    }
});

test('correct password must be provided to delete account', function () {

    try{
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('profile.delete-user-form')
            ->set('password', 'wrong-password')
            ->call('deleteUser');

        $component
            ->assertHasErrors('password')
            ->assertNoRedirect();

        $this->assertNotNull($user->fresh());

       Log::channel('test_result')->info('Test passed: correct password must be provided to delete account');

    }catch(\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        ); throw $e; //allows Phpunit To Mark The TestAs Failed
    }
});
