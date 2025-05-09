<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Auth;

class ContactController extends Controller
{
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

    public function __EmployeeTable()
    {
        $snapshot = $this->database->getReference('employee')->getSnapshot();
        $rawData = $snapshot->getValue() ?? [];

        $data = [];

        if ($rawData) {
            foreach ($rawData as $pushId => $item) {
                $item['push_id'] = $pushId;
                $data[] = $item;
            }
        }

        return view('employee', compact('data'));
    }

    public function CreateEmployee()
    {
        return view('data-management-table.CreateEmployee');
    }

    public function store(Request $request)
    {
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile', 'public');
            $profilePhotoUrl = Storage::url($path);
        } else {
            $profilePhotoUrl = $request->profile_photo;
        }

        $postData = [
            'employee_id' => $request->employee_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'profile_photo' => $profilePhotoUrl,
            'role' => $request->role,
            'department' => $request->department,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'birthday' => $request->birthday,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ];

        try {
            $user = $this->auth->createUser([
                'email' => $request->email,
                'emailVerified' => false,
                'password' => $request->password ?? 'temporary123',
                'displayName' => $request->first_name . ' ' . $request->last_name,
            ]);
            $postData['firebase_uid'] = $user->uid;
        } catch (\Throwable $e) {
            return redirect('employee')->with('status', 'ไม่สามารถสร้างบัญชี Firebase Auth ได้: ' . $e->getMessage());
        }

        $postRef = $this->database->getReference($this->tablename)->push($postData);

        if ($postRef) {
            return redirect('employee')->with('status', 'เพิ่มบัญชีสำเร็จ');
        } else {
            return redirect('employee')->with('status', 'เพิ่มบัญชีไม่สำเร็จ');
        }
    }

    public function destroy($id)
    {
        try {
            $employee = $this->database->getReference("employee/{$id}")->getValue();

            if (isset($employee['firebase_uid'])) {
                $this->auth->deleteUser($employee['firebase_uid']);
            }

            $this->database->getReference("employee/{$id}")->remove();
            return redirect()->back()->with('status', 'ลบพนักงานสำเร็จ');
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'เกิดข้อผิดพลาดในการลบ: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $employeeRef = $this->database->getReference("employee/{$id}");
        $existing = $employeeRef->getValue();
        $profilePhotoUrl = $existing['profile_photo'] ?? null;

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile', 'public');
            $profilePhotoUrl = Storage::url($path);
        }

        $birthday = null;
        if ($request->birth_day && $request->birth_month && $request->birth_year) {
            $birthday = $request->birth_year . '-' .
                        str_pad($request->birth_month, 2, '0', STR_PAD_LEFT) . '-' .
                        str_pad($request->birth_day, 2, '0', STR_PAD_LEFT);
        }

        $data = [
            'employee_id' => $request->employee_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'role' => $request->role,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'birthday' => $birthday,
            'department' => $request->department,
            'is_allow_message' => $request->has('is_allow_message'),
            'profile_photo' => $profilePhotoUrl,
        ];

        // ตรวจสอบอีเมลซ้ำ
        $employees = $this->database->getReference('employee')->getValue() ?? [];
        foreach ($employees as $key => $emp) {
            if ($key !== $id && isset($emp['email']) && $emp['email'] === $request->email) {
                return redirect()->back()->withErrors(['email' => 'อัปเดตข้อมูลไม่สำเร็จ: อีเมลนี้มีอยู่ในระบบแล้ว']);
            }
        }

        $employeeRef->update($data);

        if (empty($existing['firebase_uid'])) {
            try {
                $user = $this->auth->createUser([
                    'email' => $request->email,
                    'emailVerified' => false,
                    'password' => 'temporary123',
                    'displayName' => $request->first_name . ' ' . $request->last_name,
                ]);
                $employeeRef->update(['firebase_uid' => $user->uid]);
            } catch (\Throwable $e) {
                Log::error("สร้าง Firebase UID ไม่สำเร็จ: " . $e->getMessage());
            }
        } elseif (isset($existing['firebase_uid'])) {
            try {
                $this->auth->updateUser($existing['firebase_uid'], [
                    'email' => $request->email,
                    'displayName' => $request->first_name . ' ' . $request->last_name,
                ]);
            } catch (\Throwable $e) {
                Log::error("อัปเดต Firebase Auth ไม่สำเร็จ: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('status', 'อัปเดตข้อมูลเรียบร้อย');
    }
}