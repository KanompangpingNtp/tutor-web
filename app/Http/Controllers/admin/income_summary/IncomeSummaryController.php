<?php

namespace App\Http\Controllers\admin\income_summary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CourseBooking;
use App\Models\BookingLogs;
use App\Models\CourseTeaching;
use Carbon\Carbon;

class IncomeSummaryController extends Controller
{
    // public function IncomeSummaryPage(Request $request)
    // {
    //     $logs = BookingLogs::with(['teaching.course.user']) // โหลดทุกความสัมพันธ์ที่จำเป็น
    //         ->where('status', 2)
    //         ->get();

    //     $summary = [];

    //     foreach ($logs as $log) {
    //         $teaching = $log->teaching;
    //         if (!$teaching || !$teaching->course) continue;

    //         $course = $teaching->course;
    //         $tutor = $course->user;

    //         if (!$tutor) continue;

    //         $tutorId = $tutor->id;
    //         $courseId = $course->id;

    //         $start = \Carbon\Carbon::parse($teaching->course_starttime);
    //         $end = \Carbon\Carbon::parse($teaching->course_endtime);
    //         $taughtHours = $start->diffInMinutes($end) / 60;

    //         $hourlyRate = $teaching->hourly_rate ?? 0;

    //         // ใช้ tutorId + courseId แยกรายวิชาของติวเตอร์
    //         $key = $tutorId . '_' . $courseId;

    //         if (!isset($summary[$key])) {
    //             $summary[$key] = [
    //                 'tutor' => $tutor,
    //                 'course' => $course,
    //                 'total_income' => 0,
    //                 'total_hours' => 0,
    //             ];
    //         }

    //         $summary[$key]['total_income'] += $hourlyRate;
    //         $summary[$key]['total_hours'] += $taughtHours;
    //     }

    //     // แปลงเป็น collection
    //     $summary = collect($summary);

    //     return view('dashboard.admin.income_summary.page', compact('summary'));
    // }

    public function IncomeSummaryPage(Request $request)
    {
        $logsQuery = BookingLogs::with(['teaching.course.user'])
            ->where('status', 2);

        if ($request->filled('month') && $request->filled('year')) {
            $logsQuery->whereMonth('created_at', $request->month)
                ->whereYear('created_at', $request->year);
        }

        $logs = $logsQuery->get();

        $summary = [];

        foreach ($logs as $log) {
            $teaching = $log->teaching;
            if (!$teaching || !$teaching->course) continue;

            $course = $teaching->course;
            $tutor = $course->user;

            if (!$tutor) continue;

            $tutorId = $tutor->id;
            $courseId = $course->id;

            $start = Carbon::parse($teaching->course_starttime);
            $end = Carbon::parse($teaching->course_endtime);
            $taughtHours = $start->diffInMinutes($end) / 60;

            $hourlyRate = $teaching->hourly_rate ?? 0;

            $key = $tutorId . '_' . $courseId;

            if (!isset($summary[$key])) {
                $summary[$key] = [
                    'tutor' => $tutor,
                    'course' => $course,
                    'total_income' => 0,
                    'total_hours' => 0,
                ];
            }

            $summary[$key]['total_income'] += $hourlyRate;
            $summary[$key]['total_hours'] += $taughtHours;
        }

        $summary = collect($summary);

        $monthsYearsRaw = BookingLogs::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year')
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $monthsAvailable = $monthsYearsRaw->pluck('month')->unique()->toArray();
        $yearsAvailable = $monthsYearsRaw->pluck('year')->unique()->toArray();

        $minYear = !empty($yearsAvailable) ? min($yearsAvailable) : now()->year;
        $maxYear = !empty($yearsAvailable) ? max($yearsAvailable) : now()->year;
        $allYears = range($minYear, $maxYear);

        $allMonths = range(1, 12);

        return view('dashboard.admin.income_summary.page', compact(
            'summary',
            'allMonths',
            'allYears',
            'monthsAvailable',
            'yearsAvailable',
            'monthsYearsRaw'
        ));
    }
}
