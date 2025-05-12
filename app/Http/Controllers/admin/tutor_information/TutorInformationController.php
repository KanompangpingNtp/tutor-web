<?php

namespace App\Http\Controllers\admin\tutor_information;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TeacherResumes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class TutorInformationController extends Controller
{
    public function TutorInformationPage()
    {
        $users = User::all();

        return view('dashboard.admin.tutor_information.page', compact('users'));
    }

    public function updateTutorInformation(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->only(['name', 'email', 'level']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    }

    public function deleteTutorInformation($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

    public function TutorInformationDetailPage($id)
    {
        $user = User::with('teacherResume')->findOrFail($id);

        return view('dashboard.admin.tutor_information.tutor_information_detail.page', [
            'users' => $user,
            'teacherResume' => $user->teacherResume, // ส่งข้อมูล teacherResume ไปยัง view
        ]);
    }

    // public function AdminTeacherResumeCreate(Request $request, $id)
    // {
    //     $request->validate([
    //         'awards' => 'nullable|array',
    //         'certificates' => 'nullable|array',
    //         'educations' => 'nullable|array',
    //         'teachings' => 'nullable|array',
    //         'teaching_success' => 'nullable|string',
    //     ]);

    //     // ตรวจสอบว่า TeacherResume สำหรับ user_id นี้มีข้อมูลหรือไม่
    //     $teacherResume = TeacherResumes::where('user_id', $id)->first();

    //     $awards = array_filter($request->awards);
    //     $certificates = array_filter($request->certificates);
    //     $educations = array_filter(array_map(null, $request->education_level, $request->institution, $request->year));
    //     $teachings = array_filter(array_map(null, $request->teaching_place, $request->subject, $request->teaching_duration));

    //     if ($teacherResume) {
    //         // ถ้ามีข้อมูลแล้ว ให้ทำการอัปเดตข้อมูล
    //         $teacherResume->update([
    //             'awards' => json_encode($awards),
    //             'certificates' => json_encode($certificates),
    //             'educations' => json_encode($educations),
    //             'teachings' => json_encode($teachings),
    //             'teaching_success' => $request->teaching_success,
    //         ]);
    //     } else {
    //         // ถ้าไม่มีข้อมูล ให้ทำการบันทึกข้อมูลใหม่
    //         TeacherResumes::create([
    //             'user_id' => Auth::id(),
    //             'awards' => json_encode($awards),
    //             'certificates' => json_encode($certificates),
    //             'educations' => json_encode($educations),
    //             'teachings' => json_encode($teachings),
    //             'teaching_success' => $request->teaching_success,
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จ');
    // }
    public function AdminTeacherResumeCreate(Request $request, $id)
    {
        // ตรวจสอบว่า user ที่เกี่ยวข้องมีอยู่จริง
        $user = User::findOrFail($id);

        // Validate ข้อมูลจากฟอร์ม
        $request->validate([
            'awards' => 'nullable|array',
            'certificates' => 'nullable|array',
            'educations' => 'nullable|array',
            'teachings' => 'nullable|array',
            'teaching_success' => 'nullable|string',
        ]);

        // ตรวจสอบว่า TeacherResume สำหรับ user_id นี้มีข้อมูลหรือไม่
        $teacherResume = TeacherResumes::where('user_id', $id)->first();

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
                'user_id' => $user->id, // ใช้ user_id ที่ส่งมา
                'awards' => json_encode($awards),
                'certificates' => json_encode($certificates),
                'educations' => json_encode($educations),
                'teachings' => json_encode($teachings),
                'teaching_success' => $request->teaching_success,
            ]);
        }

        return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จ');
    }

    public function AdminTutorUpdateDetails(Request $request, $id)
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
}
