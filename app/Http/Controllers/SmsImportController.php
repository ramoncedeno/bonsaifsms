<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SmsImport;
use App\Models\SendAttempt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SmsImportController extends Controller
{

    /**
     * Handle the import of the Excel file.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {

            // Get the uploaded file.
            $request->file('file');

            // Import the file using Laravel Excel
            Excel::import(new SmsImport, $request->file('file'));

            // Redirect with a successful message.
            return redirect('/sms/view')->with('success', 'All good!');

        } catch (\Exception $e) {
            // Log the error for debugging purposes.
           Log::error('Error during user import: ' . $e);
        }
    }

      /**
     * Show the import form.
     *
     * @return \Illuminate\View\View
     */
    public function showImportForm()
    {
        return view('import_sms');
    }

     /**
     * Show the import form.
     *
     * @return \Illuminate\View\View
     */
    public function index_smsview(Request $request) {
        $query = SendAttempt::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('subject', 'LIKE', "%{$search}%")
                  ->orWhere('sponsor', 'LIKE', "%{$search}%")
                  ->orWhere('identification_id', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%")
                  ->orWhere('response_id', 'LIKE', "%{$search}%");
            });
        }

        $sendAttempts = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('sms_attemptview', compact('sendAttempts'));
    }
}
