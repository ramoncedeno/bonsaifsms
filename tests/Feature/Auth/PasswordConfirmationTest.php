<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Log;


test('confirm password screen can be rendered', function () {

    try {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/confirm-password');

        $response
            ->assertSeeVolt('pages.auth.confirm-password')
            ->assertStatus(200);

        Log::channel('test_result')->info('Test passed: confirm password screen can be rendered');

    } catch (\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        ); throw $e; //allows Phpunit To Mark The TestAs Failed
    }

});




test('password can be confirmed', function () {

    try{

        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('pages.auth.confirm-password')
            ->set('password', 'password');

        $component->call('confirmPassword');

        $component
            ->assertRedirect('/dashboard')
            ->assertHasNoErrors();
        
        Log::channel('test_result')->info('Test passed: password can be confirmed');

    }catch(\Throwable $e) {
        Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        ); throw $e; //allows Phpunit To Mark The TestAs Failed

    }

});

test('password is not confirmed with invalid password', function () {

    try{

        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('pages.auth.confirm-password')
            ->set('password', 'wrong-password');

        $component->call('confirmPassword');

        $component
            ->assertNoRedirect()
            ->assertHasErrors('password');

       Log::channel('test_result')->info('Test passed: password is not confirmed with invalid password');

   }catch(\Throwable $e){

      Log::channel('test_result')->error(
            'Test failed: ' . $e->getMessage(),
            ['exception' => $e] // <- This makes the stacktrace register
        ); throw $e; //allows Phpunit To Mark The TestAs Failed

   }

});
