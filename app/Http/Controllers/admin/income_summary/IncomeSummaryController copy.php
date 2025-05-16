<?php

namespace App\Http\Controllers\admin\income_summary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CourseBooking;
use App\Models\BookingLogs;
use App\Models\CourseTeaching;

class IncomeSummaryController extends Controller
{
    public function IncomeSummaryPage(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $availableMonths = CourseBooking::selectRaw('MONTH(created_at) as month')
            ->distinct()
            ->pluck('month')
            ->toArray();

        $availableYears = CourseBooking::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->toArray();

        // หา User ที่เป็นติวเตอร์
        $tutors = User::where('level', '2')->get();

        foreach ($tutors as $tutor) {
            $income = 0;
            $totalHours = 0;

            // ดึง BookingLogs ของติวเตอร์ที่ตรงเดือนปี และ tutor_status = 1
            $bookingLogs = BookingLogs::where('user_id', $tutor->id)
                ->whereHas('bookings', function ($q) use ($month, $year) {
                    $q->where('tutor_status', 1)
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month);
                })
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->get();

            foreach ($bookingLogs as $log) {
                $courseBooking = CourseBooking::find($log->course_booking_id);
                if (!$courseBooking) continue;

                // หา CourseTeaching ที่มี scheduled_datetime ตรงกับ CourseBooking.scheduled_datetime
                // สมมติ scheduled_datetime เป็น datetime เช่น "2025-05-16 10:00:00"
                $courseTeaching = CourseTeaching::where('course_id', $courseBooking->course_id)
                    ->where('course_starttime', '<=', $courseBooking->scheduled_datetime)
                    ->where('course_endtime', '>=', $courseBooking->scheduled_datetime)
                    ->first();

                if (!$courseTeaching) {
                    // หากหาไม่ได้ อาจต้องใช้เงื่อนไขอื่น หรือข้าม
                    continue;
                }

                // คำนวณเวลาสอน (ชั่วโมง)
                $start = \Carbon\Carbon::parse($courseTeaching->course_starttime);
                $end = \Carbon\Carbon::parse($courseTeaching->course_endtime);
                $durationHours = $start->diffInMinutes($end) / 60;

                // รายได้ = ชั่วโมง * hourly_rate
                $income += $durationHours * $courseTeaching->hourly_rate;

                // รวมชั่วโมงสอน
                $totalHours += $durationHours;
            }

            $tutor->total_income = number_format($income, 2);
            $tutor->total_teaching_hours = number_format($totalHours, 2);
        }

        return view('dashboard.admin.income_summary.page', compact(
            'tutors',
            'availableMonths',
            'availableYears',
            'month',
            'year'
        ));
    }
}
