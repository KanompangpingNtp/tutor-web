<?php

namespace App\Http\Controllers\admin\booking_history;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseBooking;
use Illuminate\Support\Facades\Auth;

class BookingHistoryController extends Controller
{
    public function AdminBookingHistoryPage()
    {
        $booking = CourseBooking::with(['course', 'teachings', 'user'])->get();

        return view('dashboard.admin.booking_history.page', compact('booking'));
    }

    public function BookingHistoryUpdateStatus($id)
    {
        $booking = CourseBooking::findOrFail($id);

        $booking->status = '2';

        $booking->save();

        return redirect()->back()->with('success', 'สถานะการจองอัปเดตแล้ว');
    }

    public function AdminBookingHistoryDelete($id)
    {
        $booking = CourseBooking::findOrFail($id);
        $booking->delete();

        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

       public function BookingHistoryUpdatePayment($id)
    {
        $booking = CourseBooking::findOrFail($id);

        $booking->payment_status = '2';

        $booking->save();

        return redirect()->back()->with('success', 'สถานะการชำระเงินอัปเดตแล้ว');
    }
}
