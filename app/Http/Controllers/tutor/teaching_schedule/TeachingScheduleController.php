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
        // $booking = BookingLogs::with(['course', 'bookings.user'])->get();
        $booking = BookingLogs::with(['course', 'bookings.user'])
            ->whereHas('bookings', function ($query) {
                $query->where('status', 2);
            })
            ->get();

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
                        'course_booking_id' => $item->course_booking_id,
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
        $bookingLog = BookingLogs::with('bookings')->findOrFail($id);
        $bookingLog->status = 2;
        $bookingLog->save();

        $courseBooking = $bookingLog->bookings;
        $courseBooking->tutor_status = '2';
        $courseBooking->save();

        $times = explode(' - ', $bookingLog->scheduled_datetime);
        $startTime = \Carbon\Carbon::parse($times[0]);
        $endTime = \Carbon\Carbon::parse($times[1]);

        $taughtHours = $startTime->diffInMinutes($endTime) / 60;

        TeachingLogs::create([
            'course_id' => $bookingLog->course_id,
            'user_id' => auth()->id(),
            'log_date_time' => now(),
            'taught_hours' => $taughtHours,
            'teaching_date' => $bookingLog->teaching_schedule_day,
            'course_booking_id' => $courseBooking->id,
        ]);

        return redirect()->back()->with('success', 'อัพเดทการสอนสำเร็จ');
    }
}
