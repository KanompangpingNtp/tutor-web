<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTeaching extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'course_starttime',
        'course_endtime',
        'hourly_rate',
        'course_day_id'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function bookings()
    {
        return $this->hasMany(CourseBooking::class, 'scheduled_datetime', 'id');
    }

    public function day()
    {
        return $this->belongsTo(CourseDay::class, 'course_day_id');
    }
}
