<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Support\Str;
use App\Models\sms_transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;


class SmsTransactionController extends Controller
{

    /**
     * Send SMS confirmation.
     *
     * @param string $phone
     * @param string $message
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function sendSMS($phone, $message)
    {

        // Validate the phone number and message
        $validator = Validator::make(

            ['phone' => $phone,
             'message' => $message
            ],
            ['phone' => 'required|regex:/^[0-9]{10}$/',
             'message' => 'required|string|max:640'
             ]
        );

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

            // Normalize the message to ASCII
            $message = $this->normalizeMessage($message);

            // Prepare the URL and parameters for the SMS API request
            $url = env('BONSAIF_SMS_URL');
            $params = [
                'phone' => $phone,
                'message' => $message,
                'out' => 'json',
                'key' => env('BONSAIF_SMS_KEY'),
            ];

            // Send the request to the SMS API
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . env('BONSAIF_AUTH')
            ])->get($url, $params);

            // Log the SMS transaction in the database
            sms_transaction::create([
                'phone' => $phone,
                'message' => $message,
                'response' => $response->body(),
            ]);

            // Return the API response in JSON format along with phone and message
            return response()->json([
                'phone' => $phone,
                'message' => $message,
                'response' => json_decode($response->body()),
                'status' => $response->status()
            ], $response->status());

        } catch (Exception $e) {
            // Return the exception message with useful data for the user
            return response()->json([
                'error' => 'An error occurred while sending the SMS.',
                'message' => $e->getMessage(),
                'phone' => $phone,
                'message_content' => $message
            ], 500);
        }
    }

    /**
     * Normalize the message by converting it to ASCII.
     *
     * @param string $message
     * @return string
     */
    private function normalizeMessage($message)
    {
        // Convert the message to ASCII
        $message = Str::ascii($message);
        return $message;
    }

    // code for the operation of the sms.send form
    public function showForm()
    {
        return view('send_sms');
    }


}

