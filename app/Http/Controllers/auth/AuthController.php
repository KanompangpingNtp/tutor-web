<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function LoginPage()
    {
        return view('auth.login');
    }

    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            if (Auth::user()->level === '1') {
                return redirect()->route('AdminIndex');
            } elseif (Auth::user()->level === '2') {
                return redirect()->route('TutorIndex');
            } elseif (Auth::user()->level === '3') {
                return redirect()->route('SubjectCategory');
            }
        }

        return back()->withErrors([
            'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('Home');
    }

    public function RegisterPage()
    {
        return view('auth.register');
    }

    public function Register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 3,
        ]);

        return redirect()->route('LoginPage')->with('success', 'ลงทะเบียนเรียบร้อยแล้ว กรุณาเข้าสู่ระบบ');
    }
}
