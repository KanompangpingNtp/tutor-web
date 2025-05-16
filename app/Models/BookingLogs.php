<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'course_booking_id',
        'booking_day',
        'scheduled_datetime',
        'teaching_schedule_day',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function bookings()
    {
        return $this->belongsTo(CourseBooking::class);
    }
}
