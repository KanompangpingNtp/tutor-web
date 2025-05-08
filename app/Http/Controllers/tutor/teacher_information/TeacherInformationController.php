<?php

namespace App\Http\Controllers\tutor\teacher_information;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherInformationController extends Controller
{
    public function TeacherInformationPage ()
    {
        return view('dashboard.tutor.teacher_information.page');
    }
}
