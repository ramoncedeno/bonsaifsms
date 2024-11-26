<?php

namespace App\Imports;

use App\Http\Controllers\SmsTransactionController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;

class SmsImport implements OnEachRow, WithHeadingRow
,WithChunkReading,WithBatchInserts,ShouldQueue

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

        //ToDo antes de hacer el envío debo almacenar los datos en la base de transacciones  y recuperar los registros que no fueron enviados. para luego intentar el envío

        try {
            // Call the method to send SMS
            $this->smsController->sendSMS($phone, $message);
        } catch (\Exception $e) {
            // Log any error that occurs during SMS sending
            Log::error("Error sending SMS to {$phone}: " . $e->getMessage());
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
