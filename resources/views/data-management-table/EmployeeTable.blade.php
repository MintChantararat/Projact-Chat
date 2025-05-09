@extends('app')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            @if(session('status'))
                <h4 class="alert alert-warning mb-2">{{session('status')}}</h4>
            @endif
            @if($errors->any())
                <h4 class="alert alert-warning mb-2">{{$errors->first()}}</h4>
            @endif

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="employeeTable">
                        <thead>
                            <tr>
                                <th style="width: 10%;">รหัสพนักงาน</th>
                                <th style="width: 15%;">ชื่อ</th>
                                <th style="width: 15%;">นามสกุล</th>
                                <th style="width: 10%;">แผนก</th>
                                <th style="width: 30%;">อีเมล</th>
                                <th class="text-center" style="width: 20%;" colspan="2">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($data as $item)
                            <tr class="employee-row">
                                <td>{{ $item['employee_id'] }}</td>
                                <td>{{ $item['first_name'] }}</td>
                                <td>{{ $item['last_name'] }}</td>
                                <td>{{ $item['department'] }}</td>
                                <td>{{ $item['email'] ?? '' }}</td>
                                <!-- คอลัมน์แรกสำหรับรายละเอียดและแก้ไข -->
                                <td class="details-edit-btns text-center">
                                    <a href="javascript:void(0);" 
                                    class="btn btn-sm btn-secondary details-btn uniform-btn" 
                                    data-employee='@json($item)'
                                    style="display: inline-block;">รายละเอียด</a>

                                    <a href="javascript:void(0);" 
                                    class="btn btn-warning btn-sm edit-btn uniform-btn" 
                                    data-employee='@json($item)'
                                    style="display: none;">แก้ไข</a>
                                </td>

                                <td class="chat-delete-btns text-center">
                                    <a href="{{ route('chat.private', ['uid' => $item['push_id']]) }}" class="btn btn-sm btn-primary chat-btn uniform-btn">แชท</a>
                                    <form method="POST" action="{{ route('employee.delete', $item['push_id']) }}" 
                                        class="d-inline delete-form" 
                                        onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบพนักงานคนนี้?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger delete-btn uniform-btn" style="display: none;">ลบ</button>
                                    </form>
                                </td>
                        @empty
                            <tr>
                                <td colspan="7">ไม่พบข้อมูล</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
