<?php

namespace App\Http\Controllers\tutor\courses_offered;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use App\Models\CourseFile;
use App\Models\CourseTeaching;

class TutorCoursesOfferedController extends Controller
{
    public function TutorCoursesOfferedPage()
    {
        $subjects = Subject::whereHas('courses', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();

        $users = User::where('level', '2')
            ->where('id', auth()->id())
            ->get();

        $courses = Course::with(['subject', 'user', 'files', 'teachings'])
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
            'course_details' => 'required|string',
            'course_duration_hour' => 'required|string',
            'course_files_title' => 'file|mimes:jpg,jpeg,png|max:21200',
            'course_files' => 'nullable|array',
            'course_files.*' => 'file|mimes:jpg,jpeg,png,mp4,webm|max:51200',

            // 'course_day' => 'required|array',
            // 'course_day.*' => 'required|date',

            'course_starttime' => 'required|array',
            'course_starttime.*' => 'required|date_format:H:i',

            'course_endtime' => 'required|array',
            'course_endtime.*' => 'required|date_format:H:i|after:course_starttime.*',

            'hourly_rate' => 'required|array',
            'hourly_rate.*' => 'required|numeric|min:0',
        ]);

        // dd($request);

        $course = Course::create([
            'subject_id' => $request->subject_id,
            'user_id' => $request->tutor_id,
            'course_name' => $request->course_name,
            'course_details' => $request->course_details,
            'course_duration_hour' => $request->course_duration_hour,
        ]);

        $courseTeachingCount = count($request->course_starttime);

        for ($i = 0; $i < $courseTeachingCount; $i++) {
            CourseTeaching::create([
                'course_id' => $course->id,
                // 'course_day' => $request->course_day[$i],
                'course_starttime' => $request->course_starttime[$i],
                'course_endtime' => $request->course_endtime[$i],
                'hourly_rate' => $request->hourly_rate[$i],
            ]);
        }

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

        return redirect()->back()->with('success', 'ลบคอร์สเรียบร้อยแล้ว');
    }

    public function TutorCoursesOfferedUpdate(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $course->update([
            'subject_id' => $request->subject_id,
            'user_id' => $request->tutor_id,
            'course_name' => $request->course_name,
            'course_details' => $request->course_details,
            'course_duration_hour' => $request->course_duration_hour,
        ]);

        $submittedTeachingIds = collect($request->teaching_id)->filter()->all();
        CourseTeaching::where('course_id', $course->id)
            ->whereNotIn('id', $submittedTeachingIds)
            ->delete();

        foreach ($request->course_starttime as $i => $course_starttime) {
            $teachingId = $request->teaching_id[$i] ?? null;

            if ($teachingId) {
                CourseTeaching::where('id', $teachingId)->update([
                    'course_starttime' => $course_starttime,
                    'course_endtime' => $request->course_endtime[$i],
                    'hourly_rate' => $request->hourly_rate[$i],
                ]);
            } else {
                CourseTeaching::create([
                    'course_id' => $course->id,
                    'course_starttime' => $course_starttime,
                    'course_endtime' => $request->course_endtime[$i],
                    'hourly_rate' => $request->hourly_rate[$i],
                ]);
            }
        }

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

        return redirect()->back()->with('success', 'แก้ไขคอร์สเรียบร้อยแล้ว');
    }
}
