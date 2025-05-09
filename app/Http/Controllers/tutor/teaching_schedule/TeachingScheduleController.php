<?php

namespace App\Http\Controllers\tutor\teaching_schedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeachingScheduleController extends Controller
{
    public function TeachingSchedulePage ()
    {
        return view('dashboard.tutor.teaching_schedule.page');
    }
}
