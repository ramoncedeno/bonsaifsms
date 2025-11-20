<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SmsImport;
use Illuminate\Support\Facades\Log;

class SmsImportForm extends Component
{
    use WithFileUploads;

    public $file;

    public function importSms()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new SmsImport(auth()->id()), $this->file);
            $this->file = null;
            session()->flash('message', 'SMS import started. The process will run in the background.');
            $this->redirect(request()->header('Referer'), navigate: true); // Full page reload
        } catch (\Exception $e) {
            Log::error('Error during SMS import: ' . $e->getMessage());
            session()->flash('message', 'Error during SMS import: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sms-import-form');
    }
}
