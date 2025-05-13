<?php

namespace App\Http\Controllers\users\account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseBooking;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    // public function UsersAccount()
    // {
    //     $booking = CourseBooking::with(['course.teachings', 'teachings'])
    //         ->where('user_id', Auth::id())
    //         ->get();

    //     return view('pages.user-account.page', compact('booking'));
    // }
    public function UsersAccount()
    {
        $userBookings = CourseBooking::with(['course.teachings', 'teachings'])
            ->where('user_id', Auth::id())
            ->get();

        // ดึงข้อมูลการจองทั้งหมด เพื่อป้องกันเวลาซ้ำ
        $allBookings = CourseBooking::select('booking_date', 'scheduled_datetime')->get();

        // สร้าง booking map: [ 'YYYY-MM-DD' => [scheduled_datetime_id, ...] ]
        $bookingMap = [];
        foreach ($allBookings as $booking) {
            $date = \Carbon\Carbon::parse($booking->booking_date)->toDateString();
            $bookingMap[$date][] = $booking->scheduled_datetime;
        }

        return view('pages.user-account.page', [
            'booking' => $userBookings,
            'bookingMap' => $bookingMap
        ]);
    }

    public function schedule(Request $request, $id)
    {
        $request->validate([
            'booking_date' => 'required|date',
            'scheduled_datetime' => 'required|exists:course_teachings,id',
        ]);

        $booking = CourseBooking::findOrFail($id);
        $booking->booking_date = $request->booking_date;
        $booking->scheduled_datetime = $request->scheduled_datetime;
        $booking->note = $request->note ?? null;
        $booking->save();

        return redirect()->back()->with('success', 'บันทึกวันเวลาเรียนสำเร็จแล้ว');
    }
}
