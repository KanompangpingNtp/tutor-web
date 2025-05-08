<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'user_id', 'course_name','course_duration_hour', 'course_price','course_details'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function files()
    {
        return $this->hasMany(CourseFile::class);
    }

    public function bookings()
    {
        return $this->hasMany(CourseBooking::class);
    }
}
