@extends('app')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            @if(session('status'))
                <h4 class="alert alert-warning mb-2">{{ session('status') }}</h4>
            @endif

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 10%;">กลุ่ม</th>
                                <th style="width: 60%;">ชื่อกลุ่ม</th>
                                <th style="width: 10%;">สร้างเมื่อ</th>
                                <th colspan="2" style="width: 20%;" colspan="2">จัดการ</th>  <!-- รวมหัว 2 คอลัมน์ -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($groupChats as $groupId => $group)
                                <tr class="text-center group-row">
                                    <!-- รูปโปรไฟล์กลุ่ม -->
                                    <td>
                                        <img src="{{ $group['conversation_photo'] ?? '/images/default-group.png' }}"
                                             alt="Group"
                                             class="rounded-circle"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>

                                    <!-- ชื่อกลุ่ม -->
                                    <td class="group-name">{{ $group['conversation_name'] ?? '-' }}</td>

                                    <!-- วันที่สร้าง -->
                                    <td>{{ \Carbon\Carbon::parse($group['created_at'])->format('d/m/Y') }}</td>

                                    <!-- ปุ่มออก - แก้ไข -->
                                    <td class="leave-edit-btns text-center">
                                        <form action="{{ route('groupchat.leave', ['groupId' => $groupId]) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะออกจากกลุ่มนี้?')">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary btn-sm uniform-btn leave-btn">ออกจากกลุ่ม</button>
                                        </form>
                                        <a href="#"
                                        class="btn btn-primary btn-sm uniform-btn edit-btn"
                                        style="display: none;"
                                        data-group="{{ json_encode($group) }}"
                                        data-group-id="{{ $groupId }}">
                                        แก้ไข
                                        </a>
                                    </td>

                                    <td class="chat-edit-btns text-center">
                                        <a href="{{ route('chat.conversation', ['groupId' => $groupId]) }}" class="btn btn-primary btn-sm uniform-btn chat-btn">แชท</a>
                                        <form action="{{ route('groupchat.delete', ['groupId' => $groupId]) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบกลุ่มนี้?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm uniform-btn edit-btn" style="display: none;">ลบ</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">ไม่พบข้อมูลกลุ่มแชท</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
