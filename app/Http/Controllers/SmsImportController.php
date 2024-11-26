<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SmsImport;

class SmsImportController extends Controller
{
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
            // Import the file using Laravel Excel
            Excel::import(new SmsImport, $request->file('file'));

            return redirect()->back()->with('success', 'SMS batch processing initiated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error processing file: ' . $e->getMessage());
        }
    }
}
