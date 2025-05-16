<?php

namespace App\Http\Controllers\users\account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseBooking;
use Illuminate\Support\Facades\Auth;
use App\Models\BookingLogs;
use App\Models\CourseTeaching;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function UsersAccount()
    {
        $booking = CourseBooking::with(['course.teachings.day', 'teachings', 'course.user'])
            ->where('user_id', Auth::id())
            ->get();

        return view(
            'pages.user-account.page',
            compact('booking')
        );
    }

    // public function schedule(Request $request, $id)
    // {
    //     $request->validate([
    //         'scheduled_datetime' => 'required|array|min:1',
    //         'scheduled_datetime.*' => 'required|integer|exists:course_teachings,id',
    //         'learning_style' => 'nullable|string',
    //         'note' => 'nullable|string',
    //     ]);

    //     $booking = CourseBooking::findOrFail($id);
    //     $booking->learning_style = $request->learning_style;
    //     $booking->note = $request->note ?? null;

    //     $scheduledIds = $request->scheduled_datetime;
    //     $booking->scheduled_datetime = implode(',', $scheduledIds);
    //     $booking->save();

    //     $teachings = CourseTeaching::whereIn('id', $scheduledIds)->with('day')->get();

    //     $startDate = Carbon::parse($booking->created_at)->startOfDay();

    //     $teachingsByDay = [];
    //     foreach ($teachings as $teaching) {
    //         $dayName = $teaching->day->day_name ?? null;
    //         if ($dayName) {
    //             if (!isset($teachingsByDay[$dayName])) {
    //                 $teachingsByDay[$dayName] = [];
    //             }
    //             $teachingsByDay[$dayName][] = $teaching;
    //         }
    //     }

    //     $dates = [];
    //     $currentDate = $startDate->copy();

    //     while (count($dates) < $booking->amount_times) {
    //         $currentDayName = $currentDate->format('l');

    //         if (isset($teachingsByDay[$currentDayName])) {
    //             $dates[] = $currentDate->copy();
    //         }
    //         $currentDate->addDay();
    //     }

    //     foreach ($dates as $date) {
    //         $dayName = $date->format('l');
    //         foreach ($teachingsByDay[$dayName] as $teaching) {
    //             BookingLogs::create([
    //                 'user_id' => $booking->user_id,
    //                 'course_id' => $booking->course_id,
    //                 'course_booking_id' => $booking->id,
    //                 'booking_day' => $dayName,
    //                 'scheduled_datetime' => $teaching->course_starttime . ' - ' . $teaching->course_endtime,
    //                 'teaching_schedule_day' => $date->format('Y-m-d'),
    //                 'teaching_id' => $teaching->id,
    //                 'status' => 1,
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with('success', 'บันทึกวันเวลาเรียนสำเร็จแล้ว');
    // }

    public function schedule(Request $request, $id)
    {
        $request->validate([
            'scheduled_datetime' => 'required|array|min:1',
            'scheduled_datetime.*' => 'required|integer|exists:course_teachings,id',
            'learning_style' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $booking = CourseBooking::findOrFail($id);
        $booking->learning_style = $request->learning_style;
        $booking->note = $request->note ?? null;

        $scheduledIds = $request->scheduled_datetime;
        $booking->scheduled_datetime = implode(',', $scheduledIds);
        $booking->save();

        $teachings = CourseTeaching::whereIn('id', $scheduledIds)->with('day')->get();

        $startDate = Carbon::parse($booking->created_at)->startOfDay();

        // จัด teaching ตามวัน เช่น Monday => [teaching1, teaching2]
        $teachingsByDay = [];
        foreach ($teachings as $teaching) {
            $dayName = $teaching->day->day_name ?? null;
            if ($dayName) {
                $teachingsByDay[$dayName][] = $teaching;
            }
        }

        $requiredHours = $booking->amount_times;
        $totalHours = 0;
        $currentDate = $startDate->copy();

        // วนหาวันที่จะสอนและหยุดเมื่อครบจำนวนชั่วโมงที่กำหนด
        while ($totalHours < $requiredHours) {
            $dayName = $currentDate->format('l');

            if (isset($teachingsByDay[$dayName])) {
                foreach ($teachingsByDay[$dayName] as $teaching) {
                    $start = Carbon::parse($teaching->course_starttime);
                    $end = Carbon::parse($teaching->course_endtime);
                    $hours = $end->floatDiffInHours($start); // คำนวณจำนวนชั่วโมง

                    // ถ้าเพิ่มแล้วไม่เกินชั่วโมงที่ต้องเรียน
                    if ($totalHours + $hours <= $requiredHours) {
                        BookingLogs::create([
                            'user_id' => $booking->user_id,
                            'course_id' => $booking->course_id,
                            'course_booking_id' => $booking->id,
                            'booking_day' => $dayName,
                            'scheduled_datetime' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                            'teaching_schedule_day' => $currentDate->format('Y-m-d'),
                            'teaching_id' => $teaching->id,
                            'status' => 1,
                        ]);

                        $totalHours += $hours;

                        // ถ้าครบชั่วโมงแล้ว ออกจากลูปทั้งหมด
                        if ($totalHours >= $requiredHours) {
                            break 2;
                        }
                    }
                }
            }

            $currentDate->addDay();
        }

        return redirect()->back()->with('success', 'บันทึกวันเวลาเรียนสำเร็จแล้ว');
    }

    public function UsersAccountBooking($id)
    {
        $booking = CourseBooking::with(['course.teachings', 'teachings'])->findOrFail($id);

        return view('pages.user-account.booking_create', compact('booking'));
    }
}
