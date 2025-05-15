<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDay extends Model
{
    use HasFactory;

    protected $fillable = ['day_name'];

    public function teachings()
    {
        return $this->hasMany(CourseTeaching::class);
    }
}
