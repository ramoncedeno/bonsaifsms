<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendAttempt extends Model
{
    protected $table = 'send_attempts_test';

    protected $fillable = [
        'phone',
        'message',
        'status',
        'response_id',
        'additional_data',
    ];
}
