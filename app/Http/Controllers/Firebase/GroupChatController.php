<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class GroupChatController extends Controller
{
    protected $database;
    protected $groupChatTable;
    protected $employeeTable;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $this->database = $factory->createDatabase();
        $this->groupChatTable = 'group_chat';
        $this->employeeTable = 'employee';
    }

    // สร้างกลุ่มใหม่
    public function store(Request $request)
    {
        $request->validate([
            'conversation_name' => 'required|string|max:255',
            'push_ids' => 'required|array|min:1',
            'push_ids.*' => 'required|string',
            'conversation_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $conversationName = $request->input('conversation_name');
        $pushIds = $request->input('push_ids');
        $photoUrl = null;

        if ($request->hasFile('conversation_photo')) {
            $path = $request->file('conversation_photo')->store('conversation_photos', 'public');
            $photoUrl = Storage::url($path);
        }

        $groupData = [
            'conversation_name' => $conversationName,
            'conversation_photo' => $photoUrl,
            'created_at' => now()->toDateTimeString(),
        ];

        try {
            $newGroupRef = $this->database->getReference($this->groupChatTable)->push($groupData);
            $groupId = $newGroupRef->getKey();

            $allEmployees = $this->database->getReference($this->employeeTable)->getValue();

            $groupMembersData = [];

            foreach ($pushIds as $pushId) {
                if (isset($allEmployees[$pushId])) {
                    $employee = $allEmployees[$pushId];
                    $groupMembersData[$pushId] = [
                        'employee_id' => $employee['employee_id'] ?? '',
                        'first_name' => $employee['first_name'] ?? '',
                        'last_name' => $employee['last_name'] ?? '',
                        'joined_at' => now()->toDateTimeString(),
                    ];
                }
            }

            $this->database->getReference("{$this->groupChatTable}/{$groupId}/group_member")->update($groupMembersData);

            return redirect()->back()->with('success', 'สร้างกลุ่มสำเร็จ!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'สร้างกลุ่มไม่สำเร็จ: ' . $e->getMessage());
        }
    }

    // แสดงข้อมูลกลุ่ม + สมาชิกทั้งหมด
    public function create()
    {
        $employees = $this->database->getReference($this->employeeTable)->getValue();
        $userList = [];

        $currentEmployeeId = session('employee_id');

        if ($employees) {
            foreach ($employees as $pushId => $employee) {
                if (isset($employee['employee_id'], $employee['first_name'], $employee['last_name']) && $employee['employee_id'] !== $currentEmployeeId) {
                    $userList[] = [
                        'push_id' => $pushId,
                        'employee_id' => $employee['employee_id'],
                        'first_name' => $employee['first_name'],
                        'last_name' => $employee['last_name'],
                    ];
                }
            }
        }

        $currentUserPushId = session('firebase_uid');
        $allGroupChats = $this->database->getReference($this->groupChatTable)->getValue() ?? [];
        $groupChats = [];

        foreach ($allGroupChats as $groupId => $group) {
            if (isset($group['group_member'][$currentUserPushId]) && !isset($group['group_member'][$currentUserPushId]['left_at'])) {
                $groupChats[$groupId] = $group;
            }
        }

        return view('groupchat', compact('userList', 'groupChats'));
    }

    // ออกจากกลุ่ม
    public function leaveGroup($groupId)
    {
        $firebaseUid = session('firebase_uid');

        if (!$firebaseUid) {
            return redirect()->back()->with('error', 'ไม่สามารถระบุผู้ใช้งานได้');
        }

        try {
            $memberPath = "{$this->groupChatTable}/{$groupId}/group_member/{$firebaseUid}";

            // ตรวจสอบก่อนว่าอยู่ในกลุ่มจริง
            $member = $this->database->getReference($memberPath)->getValue();

            if (!$member) {
                return redirect()->back()->with('error', 'คุณไม่ได้อยู่ในกลุ่มนี้');
            }

            // ลบสมาชิกออกจากกลุ่มจริง ๆ
            $this->database->getReference($memberPath)->remove();

            return redirect()->back()->with('success', 'ออกจากกลุ่มเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ออกจากกลุ่มไม่สำเร็จ: ' . $e->getMessage());
        }
    }

    // ลบกลุ่ม
    public function deleteGroup($groupId)
    {
        try {
            $this->database->getReference("{$this->groupChatTable}/{$groupId}")->remove();

            return redirect()->back()->with('success', 'ลบกลุ่มสำเร็จ');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ลบกลุ่มไม่สำเร็จ: ' . $e->getMessage());
        }
    }

    // อัปเดตกลุ่ม
    public function update(Request $request, $groupId)
    {
        $request->validate([
            'conversation_name' => 'required|string|max:255',
            'push_ids' => 'required|array|min:1',
            'push_ids.*' => 'required|string',
            'conversation_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $conversationName = $request->input('conversation_name');
        $pushIds = $request->input('push_ids');
        $photoUrl = null;

        try {
            $groupRef = $this->database->getReference("{$this->groupChatTable}/{$groupId}");
            $groupData = $groupRef->getValue();

            if (!$groupData) {
                return redirect()->back()->with('error', 'ไม่พบกลุ่มนี้');
            }

            if ($request->hasFile('conversation_photo')) {
                $path = $request->file('conversation_photo')->store('conversation_photos', 'public');
                $photoUrl = Storage::url($path);
            } else {
                $photoUrl = $groupData['conversation_photo'] ?? null;
            }

            $groupRef->update([
                'conversation_name' => $conversationName,
                'conversation_photo' => $photoUrl,
            ]);

            $allEmployees = $this->database->getReference($this->employeeTable)->getValue();
            $newMembers = [];

            foreach ($pushIds as $pushId) {
                if (isset($allEmployees[$pushId])) {
                    $employee = $allEmployees[$pushId];
                    $newMembers[$pushId] = [
                        'employee_id' => $employee['employee_id'] ?? '',
                        'first_name' => $employee['first_name'] ?? '',
                        'last_name' => $employee['last_name'] ?? '',
                        'joined_at' => now()->toDateTimeString(),
                    ];
                }
            }

            $this->database->getReference("{$this->groupChatTable}/{$groupId}/group_member")->set($newMembers);

            return redirect()->back()->with('success', 'แก้ไขกลุ่มสำเร็จแล้ว');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}
