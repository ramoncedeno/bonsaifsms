<?php

namespace Tests\Feature\Livewire;

use App\Livewire\SmsAttemptView;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations; // ✅ Cambio principal
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class SmsAttemptViewTest extends TestCase
{
    use DatabaseMigrations; // ✅ En lugar de RefreshDatabase

    protected User $testUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(); // ✅ Ejecuta seeders para tener datos de prueba

        $this->testUser = User::find(1); // ✅ Usuario real del seeder
    }

    /**
     * @test
     */
    public function test_authenticated_user_can_import_sms_via_livewire(): void
    {
        try {
            $this->actingAs($this->testUser); // ✅ Usuario real
            Queue::fake();

            $file = UploadedFile::fake()->createWithContent(
                'test.csv',
                'subject,sponsor,identification_id,phone,message\nTest Subject 1,Test Sponsor 1,123456789,5666725595,Test message 1'
            );

            Livewire::test(SmsAttemptView::class)
                ->set('file', $file)
                ->call('importSms')
                ->assertSet('message', 'Importación de SMS completada con éxito.');

            Queue::assertPushed(\Maatwebsite\Excel\Jobs\QueueImport::class);

            Log::channel('test_result')->info('Test passed: Authenticated user can import SMS via Livewire.');
        } catch (\Throwable $e) {
            Log::channel('test_result')->error(
                'Test failed: Authenticated user cannot import SMS via Livewire. ' . $e->getMessage(),
                ['exception' => $e]
            );
            throw $e;
        }
    }
}
