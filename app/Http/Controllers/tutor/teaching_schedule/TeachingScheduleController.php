<?php

namespace App\Http\Controllers\tutor\teaching_schedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseBooking;
use App\Models\TeachingLogs;
use App\Models\BookingLogs;
use Illuminate\Support\Facades\Auth;

class TeachingScheduleController extends Controller
{
    public function TeachingSchedulePage()
    {
        $booking = BookingLogs::with(['course', 'bookings.user'])->get();

        $events = $booking
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->teaching_schedule_day)->toDateString();
            })
            ->map(function ($items) {
                return $items->unique('course_id')->map(function ($item) {
                    // แยกช่วงเวลา scheduled_datetime ออกเป็น start กับ end
                    $times = explode(' - ', $item->scheduled_datetime);
                    $startTime = $times[0] ?? null;
                    $endTime = $times[1] ?? null;

                    // สร้างตัวแปร time ให้อยู่ในรูปแบบ "HH:mm - HH:mm" หรือ "-" ถ้าไม่มีข้อมูล
                    $timeString = '-';
                    if ($startTime && $endTime) {
                        $timeString = \Carbon\Carbon::parse($startTime)->format('H:i') . ' - ' . \Carbon\Carbon::parse($endTime)->format('H:i');
                    } elseif ($startTime) {
                        // ถ้ามีแค่เวลาเริ่มต้น
                        $timeString = \Carbon\Carbon::parse($startTime)->format('H:i');
                    }

                    return [
                        'id' => $item->id,
                        'name' => optional($item->user)->name ?? '-',
                        'time' => $timeString,
                    ];
                })->values();
            });

        return view('dashboard.tutor.teaching_schedule.page', compact('events'));
    }

    public function TeachingScheduleDetails($id)
    {
        $bookings = BookingLogs::with('course', 'bookings')->findOrFail($id);

        return view('dashboard.tutor.teaching_schedule.teaching_schedule_details.page_details', compact('bookings'));
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
