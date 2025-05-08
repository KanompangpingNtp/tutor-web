<?php

namespace App\Http\Controllers\tutor\teacher_information;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPerformance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherInformationController extends Controller
{
    public function TeacherInformationPage()
    {
        $users = User::find(Auth::id());

        return view('dashboard.tutor.teacher_information.page', compact('users'));
    }
}
