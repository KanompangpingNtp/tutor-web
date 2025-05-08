<?php

namespace App\Http\Controllers\admin\subject;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function SubjectPage()
    {
        $subjects = Subject::all();

        return view('dashboard.admin.subject.page', compact('subjects'));
    }

    public function SubjectCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $exists = Subject::where('name', $request->name)->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'มีวิชานี้แล้ว');
        }

        Subject::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'เพิ่มวิชาเรียบร้อยแล้ว');
    }

    public function SubjectUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $subject = Subject::find($id);

        if (!$subject) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลวิชานี้');
        }

        $subject->name = $request->name;
        $subject->save();

        return redirect()->back()->with('success', 'อัปเดตวิชาเรียบร้อยแล้ว');
    }

    public function SubjectDelete($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลวิชานี้');
        }

        $subject->delete();

        return redirect()->back()->with('success', 'ลบวิชาเรียบร้อยแล้ว');
    }
}
