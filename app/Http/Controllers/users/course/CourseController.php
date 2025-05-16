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

        $bookings = CourseBooking::where('course_id', $id)->get();

        return view('pages.course_bookings.course_bookings', compact('courses', 'bookings'));
    }

    public function BookingCreate(Request $request)
    {

        $transferSlipPath = null;
        if ($request->hasFile('transfer_slip')) {
            $transferSlipPath = $request->file('transfer_slip')->store('transfer_slips', 'public');
        }

        CourseBooking::create([
            'course_id' => $request->course_id,
            'user_id' => auth()->id(),
            'note' => $request->note,
            'status' => 1,
            'payment_status' => $request->payment_status,
            'transfer_slip' => $transferSlipPath,
            'amount_times' => $request->amount_times,
        ]);

        return redirect()->back()->with('success', 'จองวันคอร์สเรียนเรียบร้อย');
    }
}
