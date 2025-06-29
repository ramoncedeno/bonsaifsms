<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SendAttempt;

class SmsSummary extends Component
{
    public $successfulSmsCount;
    public $failedSmsCount;

    protected $listeners = ['smsImported' => 'loadSmsSummary'];

    public function mount()
    {
        $this->loadSmsSummary();
    }

    public function loadSmsSummary()
    {
        $this->successfulSmsCount = SendAttempt::where('status', 'sent')->count();
        $this->failedSmsCount = SendAttempt::whereIn('status', ['error', 'processed'])->count(); // Changed to include 'processed'
    }

    public function render()
    {
        return view('livewire.sms-summary');
    }
}