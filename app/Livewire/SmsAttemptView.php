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
    public function render()
    {
        return view('livewire.sms-attempt-view')->layout('layouts.app');
    }
}
