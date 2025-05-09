<?php

namespace App\Http\Controllers\users\account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseBooking;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function UsersAccount()
    {
        $booking = CourseBooking::with(['course', 'teachings'])
            ->where('user_id', Auth::id())
            ->get();

        return view('pages.user-account.page', compact('booking'));
    }
}
