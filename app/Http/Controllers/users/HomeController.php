<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\Course;

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

        $courses = Course::with('user', 'files')
            ->where('subject_id', $id)
            ->get();

        return view('pages.course', compact('subject', 'courses'));
    }

    public function TeacherHistoryPage ()
    {
        return view('pages.teacher-history');
    }
}
