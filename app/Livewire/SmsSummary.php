<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SendAttempt;

class SmsSummary extends Component
{
    public $summaryData = [];

    protected $listeners = ['smsImported' => 'loadSmsSummary'];

    public function mount()
    {
        $this->loadSmsSummary();
    }

    public function loadSmsSummary()
    {
        $summary = SendAttempt::selectRaw("
            COUNT(CASE WHEN status = 'sent' THEN 1 END) as successful,
            COUNT(CASE WHEN status IN ('error', 'processed') THEN 1 END) as failed
        ")->first();

        $this->summaryData = [
            ['status' => 'Successful', 'count' => $summary->successful ?? 0],
            ['status' => 'Failed', 'count' => $summary->failed ?? 0],
        ];
    }

    public function render()
    {
        return view('livewire.sms-summary', [
            'summaryData' => $this->summaryData,
        ]);
    }
}