<?php

namespace App\Http\Controllers\admin\tutor_information;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
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

    public function TutorInformationDetailPage()
    {
        return view('dashboard.admin.tutor_information.tutor_information_detail.page');
    }
}
