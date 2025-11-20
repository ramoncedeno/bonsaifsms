<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SendAttempt;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class SmsAttemptTable extends Component
{
    use WithPagination;

    public $search = '';
    public $filterOption = 'mine'; // 'mine' or 'all'

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterOption()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = SendAttempt::query();

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

        if ($this->filterOption === 'mine' && Auth::check()) {
            $query->where('user_id', Auth::id());
        }

        $sendAttempts = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.sms-attempt-table', [
            'sendAttempts' => $sendAttempts,
        ]);
    }
}
