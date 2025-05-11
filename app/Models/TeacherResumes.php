<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherResumes extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'awards',
        'certificates',
        'educations',
        'teachings',
        'teaching_success',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
