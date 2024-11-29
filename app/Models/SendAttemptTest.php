<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendAttemptTest extends Model
{
    protected $table = 'send_attempts_test';

    protected $fillable = [
        'phone',
        'message',
        'status',
        'response_id',
        'aditional_data' // campo esta mal escrito
    ];

}
