<?php

namespace App\Http\Controllers\admin\courses_offered;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use App\Models\CourseFile;
use Illuminate\Support\Facades\Storage;

class CoursesOfferedController extends Controller
{
    public function CoursesOfferedPage()
    {
        $subjects = Subject::all();
        $users = User::where('level', '2')->get();
        $courses = Course::with(['subject', 'user', 'files'])->latest()->get();

        return view('dashboard.admin.courses_offered.page', compact('subjects', 'users', 'courses'));
    }

    public function CoursesOfferedCreate(Request $request)
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

    public function deleteCourse($id)
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

    public function updateCourse(Request $request, $id)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'tutor_id' => 'required|exists:users,id',
            'course_name' => 'required|string',
            'course_duration_hour' => 'required|numeric',
            'course_price' => 'required|numeric',
            'course_details' => 'required|string',
            'course_files_title' => 'nullable|file|mimes:jpg,jpeg,png|max:21200',
            'course_files' => 'nullable|array',
            'course_files.*' => 'file|mimes:jpg,jpeg,png,mp4,webm|max:51200',
        ]);

        $course = Course::findOrFail($id);
        $course->update([
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

        return redirect()->back()->with('success', 'อัปเดตคอร์สเรียบร้อยแล้ว');
    }

    public function deleteFile($id)
    {
        $file = CourseFile::findOrFail($id);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return redirect()->back()->with('success', 'ลบไฟล์เรียบร้อยแล้ว');
    }
}
