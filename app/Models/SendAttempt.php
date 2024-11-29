<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendAttempt extends Model
{
    protected $table = 'send_attempts';

    protected $fillable = [

        'subject',
        'sponsor',
        'identification_id',
        'phone',
        'message',
        'status',
        'response_id',
        'aditional_data',

    ];

}
