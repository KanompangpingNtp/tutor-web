<?php

namespace App\Http\Controllers\users\course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseBooking;
use Carbon\Carbon;

class CourseController extends Controller
{
    public function BookingPage($id)
    {
        $courses = Course::with('user', 'files', 'teachings')->find($id);

        // ดึงรายการ booking ที่มี course_id นี้
        $bookings = CourseBooking::where('course_id', $id)->get();

        return view('pages.course_bookings.course_bookings', compact('courses', 'bookings'));
    }

    public function BookingCreate(Request $request)
    {
        $request->validate([
            'booking_date' => 'required|date',
            'scheduled_datetime' => 'required|exists:course_teachings,id',
            'note' => 'nullable|string|max:255',
            'payment_status' => 'required|in:pending,confirmed',
            'transfer_slip' => 'required|file|mimes:jpeg,png|max:10240',
            'course_id' => 'required|exists:courses,id',
        ]);

        // dd($request);

        $transferSlipPath = null;
        if ($request->hasFile('transfer_slip')) {
            $transferSlipPath = $request->file('transfer_slip')->store('transfer_slips', 'public');
        }

        CourseBooking::create([
            'course_id' => $request->course_id,
            'user_id' => auth()->id(),
            'booking_date' => $request->booking_date,
            'scheduled_datetime' => $request->scheduled_datetime,
            'note' => $request->note,
            'status' => 1,
            'payment_status' => $request->payment_status,
            'transfer_slip' => $transferSlipPath,
        ]);

        return redirect()->back()->with('success', 'จองวันเวลาเรียนเรียบร้อยแล้ว');
    }
}
