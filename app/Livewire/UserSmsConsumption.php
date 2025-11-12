<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB; // Import DB facade

class UserSmsConsumption extends Component
{
    public $currentSmsLimit;
    public $smsSent;
    public $smsRemaining;

    public function mount()
    {
        $user = Auth::user();

        if ($user) {
            // Fetch user data with calculated SMS fields, similar to SmsConsumptionDashboard
            $userData = User::where('id', $user->id)
                ->select(
                    'users.*',
                    DB::raw('users.sms_limit + users.sms_balance as current_sms_limit'),
                    DB::raw('(users.sms_limit + users.sms_balance) - (SELECT COUNT(*) FROM send_attempts WHERE send_attempts.user_id = users.id) as sms_remaining'),
                    DB::raw('(SELECT COUNT(*) FROM send_attempts WHERE send_attempts.user_id = users.id) as sms_sent')
                )
                ->first();

            if ($userData) {
                $this->currentSmsLimit = $userData->current_sms_limit ?? 0;
                $this->smsSent = $userData->sms_sent ?? 0;
                $this->smsRemaining = $userData->sms_remaining ?? 0;
            } else {
                // Fallback if user data somehow not found after Auth::user()
                $this->currentSmsLimit = 0;
                $this->smsSent = 0;
                $this->smsRemaining = 0;
            }
        } else {
            // Handle case where no user is authenticated
            $this->currentSmsLimit = 0;
            $this->smsSent = 0;
            $this->smsRemaining = 0;
        }
    }

    public function render()
    {
        return view('livewire.user-sms-consumption');
    }
}
