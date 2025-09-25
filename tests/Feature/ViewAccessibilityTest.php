<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ViewAccessibilityTest extends TestCase
{
    use DatabaseMigrations;


    //declares the property in which the test user object will be stored
    protected User $testUser;

    // Method defined to ejcute before each test
    protected function setUp(): void
    {
        parent::setUp();

        // Run the database seeders to ensure the admin user and roles exist.
        $this->seed();

        // Find and use the first user, who is assumed to be the admin.
        $this->testUser = User::find(1);
    }

    /**
     * @dataProvider viewRoutesProvider
     */
    public function test_authenticated_user_can_access_view_routes(string $route): void
    {
        try {
            $response = $this->actingAs($this->testUser)->get($route);
            $response->assertOk();

            Log::channel('test_result')->info('Test passed: The route \'' . $route . '\' is accessible');

            $this->assertTrue(true);

        } catch (\Throwable $e) {
            Log::channel('test_result')->error(
                'Test failed: The route \'' . $route . '\' is not accessible. ' . $e->getMessage(),
                ['exception' => $e]
            );
            throw $e;
        }
    }

    public static function viewRoutesProvider(): array
    {
        return [
            ['/dashboard'],
            ['/profile'],
            ['/sms/view'],
            ['/users'],
        ];
    }
}

