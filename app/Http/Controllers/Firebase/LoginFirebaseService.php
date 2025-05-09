<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\Auth;

class LoginFirebaseService extends Controller
{
    private $auth;
    private $database;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->auth = $firebase->createAuth();
        $this->database = $firebase->createDatabase();
    }

    public function login($username, $password)
{
        // ตรวจสอบข้อมูลในฐานข้อมูล Firebase
        $reference = $this->database->getReference('employee');
        $employees = $reference->getValue();

        // เช็คว่าผลลัพธ์จาก Firebase เป็น null หรือไม่
        if ($employees === null) {
            return false; // หรือแสดงข้อความผิดพลาดที่เหมาะสม
        }

        // ค้นหาพนักงานที่มี username ตรงกับที่กรอก
        foreach ($employees as $employee_id => $employee) {
            if ($employee['username'] === $username && password_verify($password, $employee['password'])) {
                // ถ้าพบ username และรหัสผ่านตรง ให้คืนค่า employee_id
                return $employee_id;
            }
        }

        return false; // หากไม่พบ username หรือรหัสผ่านไม่ตรง
    }
}
