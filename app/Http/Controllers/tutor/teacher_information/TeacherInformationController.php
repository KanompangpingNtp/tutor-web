<?php

namespace App\Http\Controllers\tutor\teacher_information;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TeacherResumes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherInformationController extends Controller
{
    public function TeacherInformationPage()
    {
        $users = User::find(Auth::id());
        $teacherResume = TeacherResumes::where('user_id', Auth::id())->first();

        return view('dashboard.tutor.teacher_information.page', compact('users', 'teacherResume'));
    }

    public function TeacherInformationUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::findOrFail($id);

        $data = $request->only('name', 'email', 'phone');

        // ถ้ามีการอัปโหลดรูปใหม่
        if ($request->hasFile('profile_image')) {
            // ลบไฟล์เก่า (ถ้ามี)
            if ($user->profile_image && Storage::exists('public/' . $user->profile_image)) {
                Storage::delete('public/' . $user->profile_image);
            }

            // อัปโหลดไฟล์ใหม่
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }


    public function TutorTeacherResumeCreate(Request $request)
    {
        $request->validate([
            'awards' => 'nullable|array',
            'certificates' => 'nullable|array',
            'educations' => 'nullable|array',
            'teachings' => 'nullable|array',
            'teaching_success' => 'nullable|string',
        ]);

        // ตรวจสอบว่า TeacherResume สำหรับ user_id นี้มีข้อมูลหรือไม่
        $teacherResume = TeacherResumes::where('user_id', Auth::id())->first();

        $awards = array_filter($request->awards ?? []);
        $certificates = array_filter($request->certificates ?? []);
        $educations = array_filter(array_map(null, $request->education_level ?? [], $request->institution ?? [], $request->year ?? []));
        $teachings = array_filter(array_map(null, $request->teaching_place ?? [], $request->subject ?? [], $request->teaching_duration ?? []));

        if ($teacherResume) {
            // ถ้ามีข้อมูลแล้ว ให้ทำการอัปเดตข้อมูล
            $teacherResume->update([
                'awards' => json_encode($awards),
                'certificates' => json_encode($certificates),
                'educations' => json_encode($educations),
                'teachings' => json_encode($teachings),
                'teaching_success' => $request->teaching_success,
            ]);
        } else {
            // ถ้าไม่มีข้อมูล ให้ทำการบันทึกข้อมูลใหม่
            TeacherResumes::create([
                'user_id' => Auth::id(),
                'awards' => json_encode($awards),
                'certificates' => json_encode($certificates),
                'educations' => json_encode($educations),
                'teachings' => json_encode($teachings),
                'teaching_success' => $request->teaching_success,
            ]);
        }

        return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จ');
    }
}
