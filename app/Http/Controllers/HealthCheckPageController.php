<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HealthCheckPageController extends Controller
{
    public function index(): View
    {
        return view('health-check.index');
    }
}
