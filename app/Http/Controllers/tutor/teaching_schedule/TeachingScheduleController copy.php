<?php

namespace App\Http\Controllers\tutor\teaching_schedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseBooking;
use App\Models\TeachingLogs;
use App\Models\CourseTeaching;
use Illuminate\Support\Carbon;

class TeachingScheduleController extends Controller
{
    public function TeachingSchedulePage()
    {
        // $bookings = CourseBooking::with(['course', 'teachings.day'])
        //     ->where('status', 2)
        //     ->get();

        // $eventsByDate = [];

        // foreach ($bookings as $booking) {
        //     // ดึงวันเริ่มต้นจาก created_at
        //     $startDate = Carbon::parse($booking->created_at);

        //     // ตรวจสอบว่ามีข้อมูล teachings และ day
        //     $dayName = optional(optional($booking->teachings)->day)->day_name;

        //     if (!$dayName) {
        //         continue;
        //     }

        //     // เริ่มนับวันไปเรื่อย ๆ จนกว่าจะครบ 10 วัน
        //     $dates = [];
        //     $currentDate = clone $startDate;

        //     while (count($dates) < $booking->amount_times) {
        //         $currentDay = $currentDate->format('l'); // เช่น "Monday"
        //         if ($currentDay === $dayName) {
        //             $dateKey = $currentDate->format('Y-m-d');

        //             $eventsByDate[$dateKey][] = [
        //                 'id' => $booking->id,
        //                 'name' => optional($booking->course)->course_name,
        //             ];

        //             $dates[] = $dateKey;
        //         }

        //         $currentDate->addDay();
        //     }
        // }

        // return view('dashboard.tutor.teaching_schedule.page', [
        //     'events' => $eventsByDate,
        // ]);
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
                           })->values();
            });

             return view('dashboard.tutor.teaching_schedule.page', [
            'events' => $eventsByDate,
        ]);
    }

    // public function TeachingScheduleDetails($id)
    // {
    //     $mainBooking = CourseBooking::with('course')->findOrFail($id);

    //     $bookings = CourseBooking::with(['course', 'teachings'])
    //         ->where('course_id', $mainBooking->course_id)
    //         ->where('status', 2)
    //         ->get();

    //     return view('dashboard.tutor.teaching_schedule.teaching_schedule_details.page_details', compact('bookings', 'mainBooking'));
    // }

    // public function TeachingScheduleDetails(Request $request, $id)
    // {
    //     $mainBooking = CourseBooking::with('course')->findOrFail($id);

    //     $date = $request->query('date');  // รับวันที่จาก URL ?date=YYYY-MM-DD

    //     $bookings = CourseBooking::with(['course', 'teachings'])
    //         ->where('course_id', $mainBooking->course_id)
    //         ->where('status', 2)
    //         ->get();

    //     return view('dashboard.tutor.teaching_schedule.teaching_schedule_details.page_details', compact('bookings', 'mainBooking', 'date'));
    // }

    public function TeachingScheduleDetails(Request $request, $id)
    {
        $mainBooking = CourseBooking::with('course')->findOrFail($id);
        $date = $request->query('date');  // รับวันที่จาก URL ?date=YYYY-MM-DD

        $bookings = CourseBooking::with(['course', 'teachings'])
            ->where('course_id', $mainBooking->course_id)
            ->where('status', 2)
            ->get();

        $teachingLogs = TeachingLogs::whereIn('course_booking_id', $bookings->pluck('id'))
            ->where('user_id', auth()->id())
            ->whereDate('teaching_date', $date) // ใช้ whereDate แทน where ตรงๆ
            ->pluck('course_booking_id')
            ->toArray();

        return view('dashboard.tutor.teaching_schedule.teaching_schedule_details.page_details', compact('bookings', 'mainBooking', 'date', 'teachingLogs'));
    }


    public function TeachingScheduleUpdateStatus(Request $request, $id)
    {
        $booking = CourseBooking::findOrFail($id);

        $teaching = CourseTeaching::findOrFail($booking->scheduled_datetime);

        $start = \Carbon\Carbon::parse($teaching->course_starttime);
        $end = \Carbon\Carbon::parse($teaching->course_endtime);
        $durationHours = $end->floatDiffInHours($start); // ใช้ float สำหรับความแม่นยำ
        $booking->amount_times = max(0, $booking->amount_times - $durationHours);
        $booking->save();

        $logDate = $request->input('date') ?? now()->format('Y-m-d');

        TeachingLogs::create([
            'course_id' => $booking->course_id,
            'user_id' => auth()->id(),
            'log_date_time' => now(),
            'taught_hours' => $durationHours,
            'course_booking_id' => $booking->id,
            'teaching_date' => $logDate,
        ]);

        return redirect()->back()->with('success', 'อัพเดทการสอนสำเร็จ');
    }
}
