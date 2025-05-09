<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTeaching extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'course_day',
        'course_starttime',
        'course_endtime',
        'hourly_rate',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
