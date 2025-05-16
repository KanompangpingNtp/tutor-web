<?php

namespace App\Http\Controllers\tutor\teaching_history;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeachingLogs;

class TeachingHistoryController extends Controller
{
    public function TutorTeachingHistory()
    {
        $teachingLogs = TeachingLogs::with(['course', 'course.teachings', 'booking.user'])
            ->where('user_id', auth()->id())
            ->get();

        return view('dashboard.tutor.teaching_history.page', compact('teachingLogs'));
    }
}
