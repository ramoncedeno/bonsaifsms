<?php

namespace App\Imports;

use Maatwebsite\Excel\Row;
use App\Models\SendAttempt;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Http\Controllers\SmsTransactionController;

class SmsImport implements
    OnEachRow,
    WithHeadingRow
    ,
    WithChunkReading,
    WithBatchInserts,
    ShouldQueue
{
    protected $smsController;

    /**
     * Constructor to inject dependencies using the container.
     */
    public function __construct()
    {
        $this->smsController = app(SmsTransactionController::class); // Use Laravel's app container
    }

    /**
     * Handle each row of the Excel file.
     *
     * @param Row $row
     * @return void
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        // Assuming the columns are: 'phone' => phone, 'message' => message
        $phone = $row['phone'];
        $message = $row['message'];

        // Save rest in DB with pending status
        $attempt = SendAttempt::create([
            'phone' => $phone,
            'message' => $message,
            'status' => 'pending',
        ]);


        try {
            // Call the method to send SMS
            $response = $this->smsController->sendSMS($phone, $message);
            $id_from_send_attempt = $response->getData()->response->result[0]->id;

            SendAttempt::updateOrCreate(
                [
                    'id' => $attempt->id,
                ],
                [
                    'status' => 'sent',
                    'response_id' => $id_from_send_attempt ? : 'Error al tratar de ubicar el ID',
                    'additional_data' => $response,
                ]
            );
        } catch (\Exception $e) {
            // Log any error that occurs during SMS sending
            Log::error($e);
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




}
