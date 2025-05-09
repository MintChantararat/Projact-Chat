<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Mail\ResetPasswordMail;

class CustomResetPasswordController extends Controller
{
    protected $auth;
    protected $database;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->auth = $factory->createAuth();
        $this->database = $factory->createDatabase();
    }

    // แสดงฟอร์มลืมรหัสผ่าน
    public function showRequestForm()
    {
        return view('auth.custom-forgot-password');
    }

    // รับอีเมลและส่งลิงก์รีเซ็ต
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $employees = $this->database->getReference('employee')->getValue();
            $firebaseUid = null;

            foreach ($employees as $uid => $user) {
                if (isset($user['email']) && $user['email'] === $request->email) {
                    $firebaseUid = $uid;
                    break;
                }
            }

            if (!$firebaseUid) {
                return back()->withErrors(['email' => 'ไม่พบอีเมลนี้ในระบบ']);
            }

            $token = Str::random(60);
            Cache::put("reset_token_{$token}", $firebaseUid, now()->addMinutes(30));

            Mail::to($request->email)->send(new ResetPasswordMail($token));

            return back()->with('status', 'ส่งลิงก์รีเซ็ตรหัสผ่านไปยังอีเมลเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            Log::error('Error sending reset email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'เกิดข้อผิดพลาดในการส่งอีเมล']);
        }
    }

    // แสดงฟอร์มตั้งรหัสใหม่
    public function showResetForm($token)
    {
        if (!Cache::has("reset_token_{$token}")) {
            return redirect()->route('login')->withErrors(['token' => 'ลิงก์หมดอายุหรือไม่ถูกต้อง']);
        }
        return view('auth.custom-reset-password', ['token' => $token]);
    }

    // รับรหัสใหม่และอัปเดต
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $token = $request->token;
        $firebaseUid = Cache::get("reset_token_{$token}");

        if (!$firebaseUid) {
            return redirect()->route('login')->withErrors(['token' => 'ลิงก์หมดอายุหรือไม่ถูกต้อง']);
        }

        // ดึง Firebase UID จริงจากฟิลด์ firebase_uid ใน employee
        $employee = $this->database->getReference("employee/{$firebaseUid}")->getValue();
        $authUid = $employee['firebase_uid'] ?? null;

        if (!$authUid) {
            return redirect()->route('login')->withErrors(['token' => 'ไม่พบ Firebase UID ในระบบ']);
        }

        try {
            // อัปเดต Firebase Authentication
            $this->auth->updateUser($authUid, ['password' => $request->password]);

            // อัปเดต Realtime Database (แบบแฮช)
            $this->database->getReference("employee/{$firebaseUid}")
                ->update(['password' => Hash::make($request->password)]);

            Cache::forget("reset_token_{$token}");

            return redirect()->route('login')->with('status', 'รีเซ็ตรหัสผ่านสำเร็จแล้ว');
        } catch (\Throwable $e) {
            Log::error('Reset password error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['password' => 'ไม่สามารถตั้งรหัสผ่านใหม่ได้']);
        }
    }
}
