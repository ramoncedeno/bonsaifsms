<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SmsConsumptionDashboard extends Component
{
    public $users;
    public $balanceAmounts = [10, 20, 50, 100];

    public function mount()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::withCount('sendAttempts')
            ->select(
                'users.*',
                DB::raw('users.sms_limit + users.sms_balance as current_sms_limit'),
                DB::raw('(users.sms_limit + users.sms_balance) - (SELECT COUNT(*) FROM send_attempts WHERE send_attempts.user_id = users.id) as sms_remaining'),
                DB::raw('(SELECT COUNT(*) FROM send_attempts WHERE send_attempts.user_id = users.id) as sms_sent')
            )
            ->get();
    }

    public function addUserBalance($userId, $amount)
    {
        $user = User::find($userId);
        if ($user) {
            $user->increment('sms_balance', $amount);
            $this->loadUsers();
            session()->flash('message', 'Balance added for ' . $user->name);
        }
    }

    public function render()
    {
        return view('livewire.sms-consumption-new', [
            'users' => $this->users,
            'balanceAmounts' => $this->balanceAmounts,
        ])->layout('layouts.app');
    }
}
