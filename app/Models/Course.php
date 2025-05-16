<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'user_id', 'course_name', 'course_details', 'course_duration_hour', 'price'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files()
    {
        return $this->hasMany(CourseFile::class);
    }

    public function bookings()
    {
        return $this->hasMany(CourseBooking::class);
    }

    public function teachings()
    {
        return $this->hasMany(CourseTeaching::class);
    }

    public function teachingLogs()
    {
        return $this->hasMany(TeachingLogs::class);
    }

    public function days()
    {
        return $this->hasMany(CourseDay::class);
    }

    public function amountTimes()
    {
        return $this->hasMany(CourseAmountTime::class);
    }

    public function bookingsLogs()
    {
        return $this->hasMany(BookingLogs::class);
    }
}
