<?php

namespace App\Imports;

use App\Http\Controllers\SmsTransactionController;
use App\Models\SendAttemptTest;
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

        // Assuming the columns are: 'phone' => phone, 'message' => message

            $subject = $row['subject'];
            $sponsor = $row['sponsor'];
            $phone = $row['phone'];
            $message = $row['message'];

         // Create a temporary record first
         $sendAttempt = SendAttemptTest::create([

             'subject' => $subject,
             'sponsor' => $sponsor,
             'phone' => $phone,
             'message' => $message,
             'status' => 'pending',

        ]);

        // recover id SendAttemptTest
        $sendAttempt_id = $sendAttempt->id;

        try {


            // Call the method to send SMS
            $response = $this->smsController->sendSMS($phone, $message);

            $id_send_message = $response->getData()->response->result[0]->id;

                        // Log the response for debugging purposes
            Log::info('SMS Response: ', ['response' => $response]);
            Log::info('SMS id_send_message: ', ['response' => $id_send_message]);

            SendAttemptTest::where('id', $sendAttempt_id)->update([

                'status' => 'sent',
                'response_id'=>$id_send_message,
                'aditional_data' => $response,  // el campo esta mal escrito
            ]);



        } catch (\Exception $e) {
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




}
