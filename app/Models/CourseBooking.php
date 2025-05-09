<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'booking_date',
        'scheduled_datetime',
        'status',
        'note',
        'payment_status',
        'transfer_slip'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teachings()
    {
        return $this->belongsTo(CourseTeaching::class, 'scheduled_datetime');
    }
}
