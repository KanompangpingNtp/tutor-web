<?php

namespace App\Http\Controllers\tutor\courses_offered;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use App\Models\CourseFile;
use App\Models\CourseTeaching;
use App\Models\CourseDay;
use App\Models\CourseAmountTime;
use Illuminate\Support\Facades\Storage;

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

        $courseDay = CourseDay::all();

        $courses = Course::with(['subject', 'user', 'files', 'teachings', 'amountTimes'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('dashboard.tutor.courses_offered.page', compact('subjects', 'users', 'courses', 'courseDay'));
    }

    public function TutorCoursesOfferedCreatePage()
    {
        $subjects = Subject::whereHas('courses', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();

        $courseDay = CourseDay::all();

        $users = User::where('level', '2')
            ->where('id', auth()->id())
            ->get();

        $courses = Course::with(['subject', 'user', 'files', 'teachings', 'amountTimes'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('dashboard.tutor.courses_offered.create', compact('subjects', 'users', 'courses', 'courseDay'));
    }

    public function TutorCoursesOfferedUpdatePage($id)
    {
        $subjects = Subject::whereHas('courses', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();

        $courseDay = CourseDay::all();

        $users = User::where('level', '2')
            ->where('id', auth()->id())
            ->get();

        $course = Course::with(['subject', 'user', 'files', 'teachings', 'amountTimes'])->findOrFail($id);

        return view('dashboard.tutor.courses_offered.update', compact('subjects', 'courseDay', 'users', 'course'));
    }

    public function TutorCoursesOfferedCreate(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'tutor_id' => 'required|exists:users,id',
            'course_name' => 'required|string',
            'course_details' => 'required|string',
            'course_duration_hour' => 'required|string',

            'course_files_title' => 'nullable|file|mimes:jpg,jpeg,png|max:21200',
            'course_files' => 'nullable|array',
            'course_files.*' => 'file|mimes:jpg,jpeg,png,mp4,webm|max:51200',

            'course_day_id' => 'required|array',
            'course_day_id.*' => 'required|exists:course_days,id',

            'course_starttime' => 'required|array',
            'course_starttime.*' => 'required|date_format:H:i',

            'course_endtime' => 'required|array',
            'course_endtime.*' => 'required|date_format:H:i',

            'hourly_rate' => 'required|array',
            'hourly_rate.*' => 'required|numeric|min:0',

            'amount_time_hour' => 'nullable|array',
            'amount_time_hour.*' => 'nullable|string|max:255',

            'price' => 'required|numeric|min:0',
        ]);

        $course = Course::create([
            'subject_id' => $request->subject_id,
            'user_id' => $request->tutor_id,
            'course_name' => $request->course_name,
            'course_details' => $request->course_details,
            'course_duration_hour' => $request->course_duration_hour,
            'price' => $request->price,
        ]);

        if ($request->has('amount_time_hour')) {
            foreach ($request->amount_time_hour as $amountHour) {
                if (!empty($amountHour)) {
                    CourseAmountTime::create([
                        'course_id' => $course->id,
                        'amount_time_hour' => $amountHour,
                    ]);
                }
            }
        }

        for ($i = 0; $i < count($request->course_day_id); $i++) {
            CourseTeaching::create([
                'course_id' => $course->id,
                'course_day_id' => $request->course_day_id[$i],
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
            'users_id' => $request->tutor_id,
            'course_name' => $request->course_name,
            'course_details' => $request->course_details,
            'course_duration_hour' => $request->course_duration_hour,
            'price' => $request->price,
        ]);

        // อัปเดต/เพิ่ม/ลบข้อมูล CourseAmountTime
        $existingIds = collect($request->amount_time_hour_id)->filter()->all();

        CourseAmountTime::where('course_id', $course->id)
            ->whereNotIn('id', $existingIds)
            ->delete();

        foreach ($request->amount_time_hour as $i => $hour) {
            $id = $request->amount_time_hour_id[$i] ?? null;

            if ($id) {
                CourseAmountTime::where('id', $id)->update([
                    'amount_time_hour' => $hour,
                ]);
            } else {
                CourseAmountTime::create([
                    'course_id' => $course->id,
                    'amount_time_hour' => $hour,
                ]);
            }
        }

        $submittedTeachingIds = collect($request->teaching_id)->filter()->all();
        CourseTeaching::where('course_id', $course->id)
            ->whereNotIn('id', $submittedTeachingIds)
            ->delete();

        foreach ($request->course_starttime as $i => $starttime) {
            $teachingId = $request->teaching_id[$i] ?? null;

            $data = [
                'course_id' => $course->id,
                'course_day_id' => $request->course_day_id[$i],
                'course_starttime' => $starttime,
                'course_endtime' => $request->course_endtime[$i],
                'hourly_rate' => $request->hourly_rate[$i],
            ];

            if ($teachingId) {
                CourseTeaching::where('id', $teachingId)->update($data);
            } else {
                CourseTeaching::create($data);
            }
        }

        if ($request->has('delete_files')) {
            foreach ($request->delete_files as $fileId) {
                $file = CourseFile::find($fileId);
                if ($file) {
                    Storage::disk('public')->delete($file->file_path);
                    $file->delete();
                }
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
