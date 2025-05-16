<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use App\Models\TeacherResumes;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function Home()
    {
        return view('pages.home');
    }

    public function SubjectCategory()
    {
        $subjects = Subject::all();

        return view('pages.subject-category', compact('subjects'));
    }

    public function CoursePage($id)
    {
        $subject = Subject::findOrFail($id);

        $courses = Course::with('user', 'files', 'teachings')
            ->where('subject_id', $id)
            ->get();

        return view('pages.course', compact('subject', 'courses'));
    }

    public function CourseDetail($id)
    {
        $courses = Course::with('user', 'teachings', 'amountTimes')->find($id);

        return view('pages.detail-course', compact('courses'));
    }

    public function TeacherHistoryPage($id)
    {
        $teacherResume = TeacherResumes::with('user')->where('user_id', $id)->first();

        // dd($id, $teacherResume);

        return view('pages.teacher-history', compact('teacherResume'));
    }
}
