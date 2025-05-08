<?php

namespace App\Http\Controllers\tutor\subject;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\SubjectUser;
use Illuminate\Support\Facades\Auth;

class SubjectTutorController extends Controller
{
    public function SubjectTutorPage()
    {
        $users = User::all();
        $subjects = Subject::all();

        $subjectUsers = SubjectUser::with(['user', 'subject'])
            ->where('user_id', Auth::id())
            ->get();

        return view('dashboard.tutor.subject_users.page', compact('users', 'subjects', 'subjectUsers'));
    }

    public function SubjectTutorCreate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        // dd($request);

        $exists = SubjectUser::where('user_id', $request->user_id)
            ->where('subject_id', $request->subject_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'มีข้อมูลในระบบแล้ว');
        }

        SubjectUser::create([
            'user_id' => $request->user_id,
            'subject_id' => $request->subject_id,
        ]);

        return redirect()->back()->with('success', 'เพิ่มวิชาที่สอนได้เรียบร้อยแล้ว');
    }

    public function SubjectTutorDelete($userId, $subjectId)
    {
        $subjectUser = SubjectUser::where('user_id', $userId)
            ->where('subject_id', $subjectId)
            ->first();

        if ($subjectUser) {
            $subjectUser->delete();
            return redirect()->back()->with('success', 'ลบวิชาที่สอนได้เรียบร้อย');
        }

        return redirect()->back()->with('error', 'ไม่พบข้อมูลที่ต้องการลบ');
    }

    public function SubjectTutorUpdate(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $subjectUser = SubjectUser::findOrFail($id);

        $subjectUser->update([
            'user_id' => $request->user_id,
            'subject_id' => $request->subject_id,
        ]);

        return redirect()->back()->with('success', 'อัปเดตวิชาที่สอนเรียบร้อยแล้ว');
    }
}
