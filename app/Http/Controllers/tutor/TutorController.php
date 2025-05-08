<?php

namespace App\Http\Controllers\tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    public function TutorIndex ()
    {
        return view('dashboard.tutor.index');
    }
}
