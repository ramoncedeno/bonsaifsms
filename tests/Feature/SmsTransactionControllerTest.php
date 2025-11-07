<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class SmsTransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba que el SMS se envía y se registra en la base de datos.
     */
    public function test_it_sends_sms_and_logs_transaction_successfully()
    {
        // Arrange: Preparamos la respuesta falsa de la API y el usuario.
        $fakeApiResponse = ['status' => 'OK', 'id_mensaje' => 'fake-12345'];
        Http::fake([
            // Usar config() es mejor práctica que env() fuera de los archivos de configuración.
            config('services.bonsaif.sms_url', env('BONSAIF_SMS_URL')) . '*' => Http::response($fakeApiResponse, 200)
        ]);

        $user = User::factory()->create();
        $phone = '5576680093';
        $message = 'controller test';

        // Act: Realizamos la petición al controlador como un usuario autenticado.
        $response = $this
            ->actingAs($user)
            ->post(route('sms.send', [
                'phone' => $phone,
                'message' => $message
            ]));

        // Assert: Verificamos la respuesta HTTP, el JSON y el registro en la base de datos.
        $response->assertStatus(200);

        $response->assertJson([
            'phone' => $phone,
            'message' => $message,
            'status' => 200,
            'response' => $fakeApiResponse
        ]);

        $this->assertDatabaseHas('sms_transactions', [
            'phone' => $phone,
            'message' => Str::ascii($message),
            'response' => json_encode($fakeApiResponse)
        ]);
    }

    /**
     * Prueba que la validación falla con un teléfono incorrecto.
     */
    public function test_it_fails_validation_with_invalid_phone()
    {
        // Arrange: Preparamos un usuario y datos inválidos.
        $user = User::factory()->create();
        $phone = '123'; // Teléfono inválido
        $message = 'test';

        // Act: Realizamos la petición al controlador.
        $response = $this
            ->actingAs($user)
            ->post(route('sms.send', [
                'phone' => $phone,
                'message' => $message
            ]));

        // Assert: Verificamos que la respuesta es un error de validación y no se crearon registros.
        $response->assertStatus(400);

        $response->assertJsonStructure([
            'error' => ['phone']
        ]);

        $this->assertDatabaseCount('sms_transactions', 0);
    }
}
