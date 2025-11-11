<?php

namespace App\Http\Controllers;

use App\Models\SendAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SmsTransactionController; // Import the original controller

class SmsSenderController extends Controller
{
    public function sendSMS($phone, $message)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has enough SMS balance
        if ($user->sms_balance <= 0) {
            return response()->json([
                'message' => 'Insufficient SMS balance.',
                'current_balance' => $user->sms_balance
            ], 403); // 403 Forbidden
        }

        // Use a database transaction to ensure atomicity
        return \DB::transaction(function () use ($user, $phone, $message) {
            // Instantiate the original SmsTransactionController
            $smsTransactionController = new SmsTransactionController();

            // Call the original sendSMS method to handle sending and sms_transaction logging
            $response = $smsTransactionController->sendSMS($phone, $message);

            // Check if the original sendSMS call was successful (status code 2xx)
            $isSuccessful = $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;

            // Log the SendAttempt for dashboard counting
            SendAttempt::create([
                'user_id' => $user->id, // Capture the authenticated user's ID
                'phone' => $phone,
                'message' => $message,
                'aditional_data' => $response->getContent(), // Store the full response body
                'status' => $isSuccessful ? 'sent' : 'failed', // Determine status
            ]);

            // If SMS was sent successfully, decrement the user's balance
            if ($isSuccessful) {
                $user->decrement('sms_balance');
            }

            // Return the original response from SmsTransactionController
            return $response;
        });
    }
}