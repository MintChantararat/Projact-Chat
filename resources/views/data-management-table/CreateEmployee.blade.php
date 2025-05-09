@extends('app')

@section('content')
<!-- พื้นหลังโปร่ง -->
<div id="create-employee-overlay" class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-none justify-content-center align-items-center" style="z-index: 1050;">
  <div class="card shadow-lg" style="width: 800px; max-width: 95%;">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="mb-0 text-center w-100">เพิ่มบัญชีผู้ใช้</h4>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ url('add-employee') }}" enctype="multipart/form-data" onsubmit="return validateCreateEmployeeForm()">
        @csrf
        <div class="row">
          <!-- ซ้าย: รูปโปรไฟล์ -->
          <div class="col-md-4 text-center">
            <div class="mb-3 position-relative" style="width: 150px; aspect-ratio: 1 / 1; margin: 0 auto; overflow: hidden; border-radius: 15px; background-color: #f0f0f0;">
              <img id="image_preview" src="" alt="Preview" class="img-fluid w-100 h-100" style="object-fit: cover; display: none;">
            </div>
            <input type="file" name="profile_photo" id="profile_photo" class="form-control" accept="image/*" style="display: none;" onchange="previewImage(event)">
            <button type="button" class="btn btn-success btn-sm mt-2" onclick="document.getElementById('profile_photo').click();">อัปโหลดรูปโปรไฟล์</button>
          </div>

          <!-- ขวา: แบบฟอร์มข้อมูล -->
          <div class="col-md-8">
            <div class="row g-2">
              <div class="col-md-6">
                <label>รหัสพนักงาน</label>
                <input type="text" name="employee_id" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label>บทบาท</label>
                <select name="role" class="form-select" required>
                  <option value="">-- เลือก --</option>
                  <option value="user">user</option>
                  <option value="admin">admin</option>
                </select>
              </div>

              <div class="col-md-6">
                <label>ชื่อ</label>
                <input type="text" name="first_name" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label>สกุล</label>
                <input type="text" name="last_name" class="form-control" required>
              </div>

              <div class="col-md-6">
                <label>แผนก</label>
                <select name="department" class="form-select" required>
                  <option value="">-- เลือก --</option>
                  <option value="marketing">แผนกการตลาด</option>
                  <option value="accounting">แผนกการบัญชี</option>
                  <option value="sales">แผนกฝ่ายขาย</option>
                  <option value="it">แผนกไอที</option>
                  <option value="hr">แผนกบุคคล</option>
                </select>
              </div>
              <div class="col-md-6">
                <label>อีเมล</label>
                <input type="email" name="email" class="form-control" required>
              </div>

              <div class="col-md-6">
                <label>เบอร์โทรศัพท์</label>
                <input type="text" name="phone_number" class="form-control">
              </div>
              <div class="col-md-6">
                <label>วันเดือนปีเกิด</label>
                <input type="date" name="birthday" class="form-control">
              </div>

              <div class="col-md-6">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4 d-flex justify-content-end gap-2">
          <button type="button" class="btn btn-danger" onclick="closeCreateEmployee()">ยกเลิก</button>
          <button type="submit" class="btn btn-primary">บันทึก</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function previewImage(event) {
  const reader = new FileReader();
  reader.onload = function () {
    const output = document.getElementById('image_preview');
    output.src = reader.result;
    output.style.display = 'block';
  };
  reader.readAsDataURL(event.target.files[0]);
}

function openCreateEmployee() {
    const overlay = document.getElementById('create-employee-overlay');
    overlay.classList.remove('d-none');
    overlay.classList.add('d-flex');
}

function closeCreateEmployee() {
    const overlay = document.getElementById('create-employee-overlay');
    overlay.classList.remove('d-flex');
    overlay.classList.add('d-none');
}

function validateCreateEmployeeForm() {
  const requiredFields = document.querySelectorAll('#create-employee-overlay [required]');
  for (let field of requiredFields) {
    if (!field.value.trim()) {
      alert('กรุณากรอกข้อมูลในช่อง "' + (field.previousElementSibling?.innerText || field.name) + '"');
      field.focus();
      return false;
    }
  }
  return true;
}
</script>

@endsection
