<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'log_date_time',
        'taught_hours',
        'teaching_date',
        'course_booking_id'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(CourseBooking::class, 'course_booking_id');
    }
}
