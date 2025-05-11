<?php

namespace App\Http\Controllers\tutor\teaching_schedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseBooking;
use App\Models\TeachingLogs;
use Illuminate\Support\Facades\Auth;

class TeachingScheduleController extends Controller
{
    public function TeachingSchedulePage()
    {
        $booking = CourseBooking::with(['course', 'teachings'])->get();

        $events = $booking
            ->filter(function ($item) {
                return $item->status == 2;
            })
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->booking_date)->format('Y-m-d');
            })
            ->map(function ($items) {
                return $items->unique('course_id')->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->course->course_name,
                    ];
                })->values();
            });


        return view('dashboard.tutor.teaching_schedule.page', compact('events'));
    }


    public function TeachingScheduleDetails($id)
    {
        $mainBooking = CourseBooking::with('course')->findOrFail($id);

        $bookings = CourseBooking::with(['course', 'teachings'])
            ->where('course_id', $mainBooking->course_id)
            ->whereDate('booking_date', $mainBooking->booking_date)
            ->where('status', 2)
            ->orderBy('booking_date')
            ->get();

        return view('dashboard.tutor.teaching_schedule.teaching_schedule_details.page_details', compact('bookings', 'mainBooking'));
    }

    public function TeachingScheduleUpdateStatus($id)
    {
        $booking = CourseBooking::find($id);
        $booking->tutor_status = '1';
        $booking->save();

        TeachingLogs::create([
            'course_id' => $booking->course_id,
            'user_id' => auth()->id(),
            'log_date_time' => now(),
        ]);

        return redirect()->back()->with('success', 'อัพเดทการสอนสำเร็จ');
    }
}
