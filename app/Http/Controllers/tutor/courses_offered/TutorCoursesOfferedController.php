<?php

namespace App\Http\Controllers\tutor\courses_offered;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use App\Models\CourseFile;
use Illuminate\Support\Facades\Storage;

class TutorCoursesOfferedController extends Controller
{
    public function TutorCoursesOfferedPage()
    {
        $subjects = Subject::all();
        $users = User::where('level', '2')
            ->where('id', auth()->id())
            ->get();

        $courses = Course::with(['subject', 'user', 'files'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('dashboard.tutor.courses_offered.page', compact('subjects', 'users', 'courses'));
    }

    public function TutorCoursesOfferedCreate(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'tutor_id' => 'required|exists:users,id',
            'course_name' => 'required|string',
            'course_duration_hour' => 'required|numeric',
            'course_price' => 'required|numeric',
            'course_details' => 'required|string',
            'course_files_title' => 'file|mimes:jpg,jpeg,png|max:21200',
            'course_files' => 'nullable|array',
            'course_files.*' => 'file|mimes:jpg,jpeg,png,mp4,webm|max:51200',
        ]);

        // dd($request);

        $course = Course::create([
            'subject_id' => $request->subject_id,
            'user_id' => $request->tutor_id,
            'course_name' => $request->course_name,
            'course_duration_hour' => $request->course_duration_hour,
            'course_price' => $request->course_price,
            'course_details' => $request->course_details,
        ]);

        if ($request->hasFile('course_files_title')) {
            $file = $request->file('course_files_title');
            $path = $file->store('course_files', 'public');

            CourseFile::create([
                'course_id' => $course->id,
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'status' => 1,
            ]);
        }

        if ($request->hasFile('course_files')) {
            foreach ($request->file('course_files') as $file) {
                $path = $file->store('course_files', 'public');

                CourseFile::create([
                    'course_id' => $course->id,
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'status' => 2,
                ]);
            }
        }

        return redirect()->back()->with('success', 'เพิ่มคอร์สเรียบร้อยแล้ว');
    }

    public function TutordeleteCourse($id)
    {
        $course = Course::findOrFail($id);

        foreach ($course->files as $file) {
            if (file_exists(storage_path('app/public/' . $file->file_path))) {
                unlink(storage_path('app/public/' . $file->file_path));
            }

            $file->delete();
        }

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'ลบคอร์สเรียบร้อยแล้ว');
    }
}
