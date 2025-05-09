<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // สร้างการเชื่อมต่อกับ Firebase
        $factory = (new Factory)->withServiceAccount(config_path('firebase_credentials.json'))
                                ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $database = $factory->createDatabase();

        // ดึงข้อมูลทั้งหมดจาก employee
        $employees = $database->getReference('employee')->getValue();

        // ตรวจสอบ username + password
        foreach ($employees as $pushId => $employee) {
            if (
                isset($employee['username'], $employee['password']) &&
                $employee['username'] === $username &&
                Hash::check($password, $employee['password']) // เปรียบเทียบรหัสผ่าน
            ) {
                // บันทึกข้อมูลผู้ใช้ลง session
                session([
                    'employee_id'   => $employee['employee_id'],
                    'user_name'     => $employee['first_name'] . ' ' . $employee['last_name'],
                    'user_avatar' => asset($employee['profile_photo'] ?? 'images/default-avatar.png'),
                    'role'          => $employee['role'],
                    'firebase_uid'  => $pushId, // ใช้สำหรับตรวจสอบสมาชิกกลุ่ม
                ]);

                return redirect('/home');
            }
        }

        // ถ้าไม่พบผู้ใช้
        return back()->withErrors(['error' => 'Username หรือ Password ไม่ถูกต้อง']);
    }

    public function logout()
    {
        // การ logout ใน Laravel ใช้ auth()->logout()
        auth()->logout();

        // ล้าง session ทั้งหมด
        session()->flush();

        // กลับหน้า login
        return redirect('/');
    }
}
