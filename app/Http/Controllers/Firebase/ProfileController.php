<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected $database;
    protected $tablename;
    protected $auth;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->database = $factory->createDatabase();
        $this->auth = $factory->createAuth();
        $this->tablename = 'employee';
    }

    public function showProfile(Request $request)
    {
        $firebaseUid = session('firebase_uid');

        if (!$firebaseUid) {
            return view('profile')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $snapshot = $this->database->getReference("{$this->tablename}/{$firebaseUid}")->getSnapshot();

        if ($snapshot->exists()) {
            $userData = $snapshot->getValue();
            return view('profile', ['data' => $userData, 'role' => session('role')]);
        } else {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลพนักงาน');
        }
    }

    public function update(Request $request)
    {
        $firebaseUid = session('firebase_uid');

        if (!$firebaseUid) {
            return redirect()->back()->with('error', 'ไม่พบรหัสพนักงาน');
        }

        $validated = $request->validate([
            'employee_id' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'day' => 'nullable',
            'month' => 'nullable',
            'year' => 'nullable',
            'department' => 'nullable|string',
            'email' => 'nullable|email',
            'phone_number' => 'nullable|string',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $snapshot = $this->database->getReference("{$this->tablename}/{$firebaseUid}")->getSnapshot();
        $userData = $snapshot->getValue();

        if (($userData['role'] ?? '') !== 'admin') {
            unset($validated['employee_id']);
            unset($validated['department']);
        }

        $birthday = null;
        if ($request->filled(['day', 'month', 'year'])) {
            $birthday = "{$request->input('year')}-{$request->input('month')}-{$request->input('day')}";
        }

        $newPassword = $request->input('password');
        $hashedPassword = $newPassword ? Hash::make($newPassword) : null;

        $profilePhotoUrl = $userData['profile_photo'] ?? '';
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile', 'public');
            $profilePhotoUrl = Storage::url($path);
        }

        $data = [
            'employee_id'   => $validated['employee_id'] ?? $userData['employee_id'],
            'first_name'   => $validated['first_name'],
            'last_name'    => $validated['last_name'],
            'department'   => $validated['department'] ?? $userData['department'],
            'email'        => $validated['email'] ?? '',
            'phone_number' => $validated['phone_number'] ?? '',
            'username'     => $validated['username'],
            'profile_photo' => $profilePhotoUrl ?? '',
        ];

        if ($birthday) {
            $data['birthday'] = $birthday ?? '';
        }

        if ($hashedPassword) {
            $data['password'] = $hashedPassword;
        }

        try {
            $this->database->getReference("{$this->tablename}/{$firebaseUid}")->update($data);

            if (!empty($path)) {
                session(['user_avatar' => asset('storage/' . $path)]);
            }

            // ✅ เพิ่มอัปเดตรหัสผ่าน & อีเมลใน Firebase Auth ด้วย
            if (!empty($userData['firebase_uid'])) {
                $updateData = [
                    'email' => $request->email,
                    'displayName' => $request->first_name . ' ' . $request->last_name,
                ];

                if ($request->filled('password')) {
                    $updateData['password'] = $request->password;
                }

                $this->auth->updateUser($userData['firebase_uid'], $updateData);
            }

            return redirect()->back()->with('success', 'อัปเดตข้อมูลสำเร็จแล้ว');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}
