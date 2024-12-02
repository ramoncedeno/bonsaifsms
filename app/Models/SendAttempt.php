<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SendAttempt extends Model implements Auditable

{
    use \OwenIt\Auditing\Auditable;
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
