<?php

namespace App\Http\Controllers;

use App\Models\SendAttempt;

class SendAttemptController extends Controller
{
    public function index()
    {
        // Filtrar registros con status 'sent' y ordenarlos por 'created_at' en orden descendente
        $sendAttempts = SendAttempt::where('status', 'sent')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        // Pasar los datos filtrados y ordenados a la vista
        return view('viewsend_attempts', compact('sendAttempts'));
    }

}
