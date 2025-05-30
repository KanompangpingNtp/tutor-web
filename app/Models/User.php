<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'profile_image',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_users');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function bookings()
    {
        return $this->hasMany(CourseBooking::class);
    }

    public function teachingLogs()
    {
        return $this->hasMany(TeachingLogs::class);
    }

    public function teacherResume()
    {
        return $this->hasOne(TeacherResumes::class);
    }

    public function bookingsLogs()
    {
        return $this->hasMany(BookingLogs::class);
    }
}
