<!-- ซ่อนการ์ดด้วย class d-none ในตอนเริ่มต้น -->
<div id="editEmployeeCard" class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-none justify-content-center align-items-center" style="z-index: 1050;">
    <div class="card p-4" style="width: 900px; max-width: 90%;">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="mb-1 text-center w-100">แก้ไขข้อมูลพนักงาน</h4>
    </div>
        <div class="card-body">
            <form id="editEmployeeForm" method="POST" enctype="multipart/form-data">
                <div class="row">
                    @csrf
                    <!-- รูปโปรไฟล์ -->
                    <div class="col-md-4 text-center">
                        <div class="mb-3 position-relative" style="width: 150px; aspect-ratio: 1 / 1; margin: 0 auto; overflow: hidden; border-radius: 15px; background-color: #f0f0f0;">
                            <img id="profileImage" src="{{ asset('path/to/default-avatar.png') }}" alt="Profile" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*" style="display: none;" onchange="previewEditProfileImage(event)">
                        </div>
                        <button type="button" class="btn btn-success btn-sm" onclick="document.getElementById('profilePhotoInput').click();">
                            อัปโหลดรูปโปรไฟล์
                        </button>
                    </div>
                    <!-- ฟอร์มข้อมูล -->
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>รหัสพนักงาน</label>
                                <input type="text" class="form-control" name="employee_id" id="employee_id">
                            </div>
                            <div class="col-md-6">
                                <label>บทบาท</label>
                                <select class="form-select" name="role" id="role">
                                    <option value="admin">admin</option>
                                    <option value="user">user</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>ชื่อ</label>
                                <input type="text" class="form-control" name="first_name" id="first_name">
                            </div>
                            <div class="col-md-6">
                                <label>สกุล</label>
                                <input type="text" class="form-control" name="last_name" id="last_name">
                            </div>
                            <div class="col-md-12">
                                <label>อีเมล</label>
                                <input type="email" class="form-control" name="email" id="email">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label>เบอร์โทรศัพท์</label>
                                <input type="text" class="form-control" name="phone_number" id="phone_number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">วัน/เดือน/ปีเกิด</label>
                                @php
                                    $day = $month = $year = '';
                                    if (!empty($data['birthday'])) {
                                    $parts = explode('-', $data['birthday']);
                                    $year = $parts[0] ?? '';
                                    $month = $parts[1] ?? '';
                                    $day = $parts[2] ?? '';
                                    }
                                @endphp
                                <div class="d-flex gap-2">
                                    <div class="col-md-4">
                                    <select name="birth_day" id="birth_day" class="form-select">
                                        <option value="">-- วัน --</option>
                                        @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $day == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    </div>

                                    <div class="col-md-4">
                                    <select name="birth_month" id="birth_month" class="form-select">
                                        <option value="">-- เดือน --</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    </div>

                                    <div class="col-md-5">
                                    <select name="birth_year" id="birth_year" class="form-select">
                                        <option value="">-- ปี --</option>
                                        @for ($i = date('Y'); $i >= 1900; $i--)
                                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!--<div class="col-md-6">
                                <label>Username</label>
                                <input type="text" class="form-control" name="username" id="username">
                            </div>-->
                            <div class="col-md-6">
                                <label>แผนก</label>
                                <select class="form-select" name="department" id="department">
                                    <option value="marketing">แผนกการตลาด</option>
                                    <option value="accounting">แผนกการบัญชี</option>
                                    <option value="sales">แผนกฝ่ายขาย</option>
                                    <option value="it">แผนกไอที</option>
                                    <option value="hr">แผนกบุคคล</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end" style="visibility: hidden;">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_allow_message" id="is_allow_message">
                                    <!--<label class="form-check-label">สิทธิ์ในการส่งข้อความประชาสัมพันธ์</label>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ปุ่มด้านล่าง -->
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" onclick="hideEditCard()">ย้อนกลับ</button>
                    <button type="button" class="btn btn-danger" onclick="hideEditCard()">ยกเลิก</button>
                    <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewEditProfileImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById('profileImage').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
<script>
function showEditCard(data) {
    const card = document.getElementById('editEmployeeCard');
    card.classList.remove('d-none');
    card.classList.add('d-flex');

    // เซ็ต action ด้วย push_id
    document.getElementById('editEmployeeForm').action = `/employee/${data.push_id}/update`;
    // รูป
    document.getElementById('profileImage').src = data.profile_photo || '{{ asset("path/to/default-avatar.png") }}';

    // ฟอร์มทั่วไป
    document.getElementById('employee_id').value = data.employee_id ?? '';
    document.getElementById('first_name').value = data.first_name ?? '';
    document.getElementById('last_name').value = data.last_name ?? '';
    document.getElementById('role').value = data.role ?? 'user';
    document.getElementById('email').value = data.email ?? '';
    document.getElementById('phone_number').value = data.phone_number ?? '';
    document.getElementById('department').value = data.department ?? '';
    document.getElementById('is_allow_message').checked = data.is_allow_message ?? false;

    // วัน/เดือน/ปีเกิด
    if (data.birthday) {
        const parts = data.birthday.split('-');
        document.getElementById('birth_year').value = parts[0];
        document.getElementById('birth_month').value = parts[1];
        document.getElementById('birth_day').value = parts[2];
    }
}

function hideEditCard() {
    const card = document.getElementById('editEmployeeCard');
    card.classList.add('d-none');
    card.classList.remove('d-flex');
}
</script>

@endpush
