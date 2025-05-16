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

        // ดึง teachings กับ day_name ของแต่ละ teaching
        $teachings = CourseTeaching::whereIn('id', $scheduledIds)->with('day')->get();

        // เริ่มต้นวันนับจากวันที่สร้าง booking
        $startDate = Carbon::parse($booking->created_at)->startOfDay();

        $dayName = [];

        $time = [];
        foreach ($teachings as $teaching) {
            $dayName[] = $teaching->day->day_name ?? null;
            $time[$teaching->day->day_name] = $teaching->course_starttime . ' - ' . $teaching->course_endtime;
            if (!$dayName) continue;
        }

        $dates = [];
        $currentDate = $startDate->copy();

        while (count($dates) < $booking->amount_times) {
            if (in_array($currentDate->format('l'), $dayName)) {
                $dates[] = $currentDate->copy();
            }
            $currentDate->addDay();
        }

        foreach ($dates as $date) {
            $x = Carbon::parse($date->format('Y-m-d'));
            $dayName = $x->format('l');
            BookingLogs::create([
                'user_id' => $booking->user_id,
                'course_id' => $booking->course_id,
                'course_booking_id' => $booking->id,
                'booking_day' => $dayName,
                'scheduled_datetime' => $time[$dayName],
                'teaching_schedule_day' => $date->format('Y-m-d'),
            ]);
        }

        return redirect()->back()->with('success', 'บันทึกวันเวลาเรียนสำเร็จแล้ว');
    }

    public function UsersAccountBooking($id)
    {
        $booking = CourseBooking::with(['course.teachings', 'teachings'])->findOrFail($id);

        return view('pages.user-account.booking_create', compact('booking'));
    }
}
