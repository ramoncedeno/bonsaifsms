<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SendAttempt;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SmsImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Added Auth facade

class SmsAttemptView extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $file;
    public $message = '';
    public $filterOption = 'mine'; // New property for filtering: 'mine' or 'all'

    // Removed $isImporting property
    // Removed protected $listeners = ['importFinished' => 'handleImportFinished'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterOption()
    {
        $this->resetPage();
    }

    public function importSms()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new SmsImport(auth()->id()), $this->file);
            $this->file = null;
            session()->flash('message', 'Importación de SMS iniciada. La página se recargará en breve.');
            $this->redirect(request()->header('Referer'), navigate: true); // Full page reload
        } catch (\Exception $e) {
            Log::error('Error durante la importación de SMS: ' . $e->getMessage());
            session()->flash('message', 'Error durante la importación de SMS: ' . $e->getMessage());
        }
    }

    // Removed handleImportFinished method

    public function render()
    {
        $query = SendAttempt::query();

        // Add user_id to the selected columns, even if not displayed
        $query->select('id', 'user_id', 'subject', 'sponsor', 'identification_id', 'phone', 'message', 'status', 'response_id', 'created_at');

        if (!empty($this->search)) {
            $searchableFields = [
                'subject',
                'sponsor',
                'identification_id',
                'phone',
                'message',
                'status',
                'response_id',
            ];

            $query->where(function($q) use ($searchableFields) {
                foreach ($searchableFields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$this->search}%");
                }
            });
        }

        // Apply filter based on filterOption
        if ($this->filterOption === 'mine' && Auth::check()) {
            $query->where('user_id', Auth::id());
        }

        $sendAttempts = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.sms-attempt-view', [
            'sendAttempts' => $sendAttempts,
        ])->layout('layouts.app');
    }
}
