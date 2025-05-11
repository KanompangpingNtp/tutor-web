<?php

namespace App\Http\Controllers\admin\income_summary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CourseBooking;

class IncomeSummaryController extends Controller
{
    public function IncomeSummaryPage(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $availableMonths = CourseBooking::selectRaw('MONTH(booking_date) as month')
            ->distinct()
            ->pluck('month')
            ->toArray();

        $availableYears = CourseBooking::selectRaw('YEAR(booking_date) as year')
            ->distinct()
            ->pluck('year')
            ->toArray();

        $tutors = User::where('level', '2')
            ->with(['courses.teachings.bookings'])
            ->get();

        foreach ($tutors as $tutor) {
            $income = 0;
            $totalHours = 0;

            foreach ($tutor->courses as $course) {
                foreach ($course->teachings as $teaching) {
                    $filteredBookings = $teaching->bookings->filter(function ($booking) use ($month, $year) {
                        $date = \Carbon\Carbon::parse($booking->booking_date);
                        return (
                            (!$month || $date->month == $month) &&
                            (!$year || $date->year == $year) &&
                            $booking->tutor_status == 1
                        );
                    });

                    $bookingsCount = $filteredBookings->count();

                    if ($bookingsCount > 0) {
                        $durationHours = \Carbon\Carbon::parse($teaching->course_starttime)
                            ->diffInMinutes(\Carbon\Carbon::parse($teaching->course_endtime)) / 60;

                        $income += $bookingsCount * $teaching->hourly_rate;

                        $totalHours += $bookingsCount * $durationHours;
                    }
                }
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
