<?php

namespace App\Imports;

use App\Http\Controllers\SmsTransactionController;
use App\Models\SendAttempt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents; // Added WithEvents
use Maatwebsite\Excel\Events\AfterImport; // Added AfterImport event
use Maatwebsite\Excel\Row;
use Livewire\Features\SupportEvents\Dispatch; // Added Livewire Dispatch

class SmsImport implements OnEachRow, WithHeadingRow, WithChunkReading, WithBatchInserts, ShouldQueue, WithEvents // Added WithEvents
{
    protected $smsController;
    public $userId; // Changed from protected to public

    /**
     * Constructor to inject dependencies using the container.
     */
    public function __construct(int $userId) // Modified to accept userId
    {
        $this->smsController = app(SmsTransactionController::class); // Use Laravel's app container
        $this->userId = $userId; // Store the userId
    }

    /**
     * Handle each row of the Excel file.
     *
     * @param Row $row
     * @return void
     */
    public function onRow(Row $row)
    {
         // Fields loaded in the file

            $subject = $row['subject'];
            $sponsor = $row['sponsor'];
            $identification_id =$row['identification_id'];
            $phone = $row['phone'];
            $message = $row['message'];

         // Create a temporary record first
         $sendAttempt = SendAttempt::create([
             'user_id' => $this->userId, // Added user_id
             'subject' => $subject,
             'sponsor' => $sponsor,
             'identification_id'=>$identification_id,
             'phone' => $phone,
             'message' => $message,
             'status' => 'pending',

        ]);

        // recover id SendAttempt
        $sendAttempt_id = $sendAttempt->id;

        try {


            // Call the method to send SMS
            $response = $this->smsController->sendSMS($phone, $message);
            //get response from sms controller

        if (isset( $response->getData()->response->result[0]->id)){

            $id_send_message = $response->getData()->response->result[0]->id;

            // updates the fields  SendAttempt
            SendAttempt::where('id', $sendAttempt_id)->update([

                'status' => 'sent',
                'response_id'=>$id_send_message,
                'aditional_data' => json_encode($response),
            ]);

        } else {

            SendAttempt::where('id', $sendAttempt_id)->update([
                'status' => 'processed',
                'aditional_data' => json_encode($response),
            ]);
        }

        } catch (\Exception $e) {

                    // Manejar cualquier excepción
                SendAttempt::where('id', $sendAttempt_id)->update([
                    'status' => 'error',
                    'aditional_data' => json_encode([
                        'error_message' => $e->getMessage(),
                        'original_response' => $response ?? null
                    ]),
                ]);

            // Log any error that occurs during SMS sending
            Log::error("Error sending SMS to {$phone}: " . $e);


        }
    }

    /**
     * Chunk size for processing data in fragments
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 300;
    }

    /**
     * * Lot size for bulk inserts.
     *
     * @return int
     */
    public function batchSize(): int
    {
        return 6000;
    }

    /**
     * Register events for the import process.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                // Dispatch Livewire event after the entire import is finished
                Dispatch::browser('importFinished', ['message' => 'Importación de SMS completada con éxito.']);
                Log::info('SMS Import finished and Livewire event dispatched with message.');
            },
        ];
    }
}
