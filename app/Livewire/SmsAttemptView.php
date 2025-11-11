<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SendAttempt;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SmsImport;
use Illuminate\Support\Facades\Log;

class SmsAttemptView extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $file;
    public $message = '';

    public function updatingSearch()
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
            $this->message = 'Importación de SMS completada con éxito.';
            $this->file = null;
            $this->resetPage(); // Reset pagination to show new data
            $this->dispatch('smsImported'); // <--- AÑADIDO: Forzar la re-renderización del componente
        } catch (\Exception $e) {
            Log::error('Error durante la importación de SMS: ' . $e->getMessage());
            $this->message = 'Error durante la importación de SMS: ' . $e->getMessage();
        }
    }

    public function render()
    {
        $query = SendAttempt::query();

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

        $sendAttempts = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.sms-attempt-view', [
            'sendAttempts' => $sendAttempts,
        ])->layout('layouts.app');
    }
}
