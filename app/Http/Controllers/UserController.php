<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    public function import()
    {
        try{

            Excel::import(new UsersImport, 'users.xlsx');
            return redirect('/')->with('success', 'All good!');

        }catch (\Exception $e){

            // Log the error if it occurs
            Log::error('Error during import: ' . $e->getMessage());
            return redirect('/')->with('error', 'Error during import. Please check the log records.');

        }
    }

     /**
     * Displays the user import form.
     */
    public function viewimportform()
    {
        return view('importusers');
    }


    /**
     * Processes the file uploaded in the form for import.
     */
    public function requestimportform(Request $request)
    {
        //Validate that a file has been uploaded
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        if ($request->hasFile('file')) {
            try {
                $file = $request->file('file');
                Excel::import(new UsersImport, $file);
                return redirect()->back()->with('success', 'Import completed successfully.');
            } catch (\Exception $e) {
                // Log the error if it occurs
                Log::error('Error during form import: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Error during import. Please check the log records.');
            }
        }

        return redirect()->back()->with('error', 'No file has been selected for import.');
    }


}
