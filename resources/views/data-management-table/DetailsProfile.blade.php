@extends('app')

@section('title', 'โปรไฟล์พนักงาน')

@section('content')
<style>
  .profile-img {
    width: 100%;
    height: 300px;
    background-color: #eee;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ccc;
  }
  .btn-upload {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
  }
  .form-control:focus {
    box-shadow: none;
    border-color: #6c63ff;
  }
  .eye-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: gray;
  }
</style>

<div class="container-fluid pt-4 pb-4 ps-5 pe-5">

  @if(session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger text-center">{{ session('error') }}</div>
  @elseif(isset($error))
    <div class="alert alert-danger text-center">{{ $error }}</div>
  @endif

  <div class="row mx-auto" style="max-width: 1140px;">
    <!-- รูปโปรไฟล์ -->
    <div class="col-md-4 text-center">
      <div class="profile-img mb-3 rounded" style="width: 80%; height: auto; margin: 0 auto; display: flex; flex-direction: column; align-items: center;">
          @if (!empty(session('user_avatar')))
              <img id="profileImagePreview" src="{{ session('user_avatar') }}" alt="Profile Photo"
                  style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
          @else
              <i class="bi bi-person-circle" style="font-size: 100px; color: #ccc;"></i>
          @endif
      </div>
        
      <!-- ปุ่มอัปโหลด -->
      <button type="button" class="btn btn-upload" style="width: 80%; margin-top: 10px;" onclick="document.getElementById('profileImageInput').click();">
          อัปโหลดรูปโปรไฟล์
      </button>
        
      <!-- input file ที่ซ่อน -->
      <input type="file" id="profileImageInput" name="profile_photo" style="display: none;" accept="image/*" onchange="previewImage(event)">
    </div>

    <script>
        // ฟังก์ชันพรีวิวรูปภาพ
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                // แสดงผลรูปโปรไฟล์ใหม่ที่ถูกเลือก
                document.getElementById('profileImagePreview').src = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>


    <!-- ฟอร์มข้อมูล -->
    <div class="col-md-7 ms-5">
      <div class="mb-3">
        <label class="form-label">รหัสพนักงาน</label>
          @if(session('role') !== 'admin')
            <input type="text" name="employee_id" class="form-control bg-light" value="{{ $data['employee_id'] ?? '' }}" readonly>
          @else
            <input type="text" name="employee_id" class="form-control" value="{{ $data['employee_id'] ?? '' }}">
          @endif
      </div>

      <div class="mb-3 row">
        <div class="col-md-6">
          <label class="form-label">ชื่อ</label>
          <input type="text" name="first_name" class="form-control" value="{{ $data['first_name'] ?? '' }}">
        </div>

        <div class="col-md-6">
          <label class="form-label">นามสกุล</label>
          <input type="text" name="last_name" class="form-control" value="{{ $data['last_name'] ?? '' }}">
        </div>
      </div>

      <div class="mb-3 row">
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
        <div class="col-4">
          <select name="day" class="form-select">
            <option value="">-- วัน --</option>
            @for ($i = 1; $i <= 31; $i++)
              <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $day == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
          </select>
        </div>

        <div class="col-4">
          <select name="month" class="form-select">
            <option value="">-- เดือน --</option>
            @for ($i = 1; $i <= 12; $i++)
              <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
          </select>
        </div>

        <div class="col-4">
          <select name="year" class="form-select">
            <option value="">-- ปี --</option>
            @for ($i = date('Y'); $i >= 1900; $i--)
              <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
          </select>
        </div>
      </div>

      <div class="mb-3">
      <label class="form-label">แผนก</label>
        <select name="department" id="department" class="form-select {{ session('role') !== 'admin' ? 'bg-light' : '' }}" {{ session('role') !== 'admin' ? 'disabled' : '' }}>
          <option value="marketing" {{ ($data['department'] ?? '') === 'marketing' ? 'selected' : '' }}>แผนกการตลาด</option>
          <option value="accounting" {{ ($data['department'] ?? '') === 'accounting' ? 'selected' : '' }}>แผนกการบัญชี</option>
          <option value="sales" {{ ($data['department'] ?? '') === 'sales' ? 'selected' : '' }}>แผนกฝ่ายขาย</option>
          <option value="it" {{ ($data['department'] ?? '') === 'it' ? 'selected' : '' }}>แผนกไอที</option>
          <option value="hr" {{ ($data['department'] ?? '') === 'hr' ? 'selected' : '' }}>แผนกบุคคล</option>
        </select>

        {{-- กรณีไม่ใช่ admin ซ่อนค่า department ไว้เพื่อให้ส่งกลับได้ --}}
        @if(session('role') !== 'admin')
            <input type="hidden" name="department" value="{{ $data['department'] ?? '' }}">
        @endif
      </div>

      <div class="mb-3">
        <label class="form-label">อีเมล</label>
        <input type="email" name="email" class="form-control" value="{{ $data['email'] ?? '' }}">
      </div>

      <div class="mb-3">
        <label class="form-label">เบอร์โทรศัพท์</label>
        <input type="tel" name="phone_number" class="form-control" value="{{ $data['phone_number'] ?? '' }}">
      </div>

      <div class="mb-3 row">
        <div class="col-md-6">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="{{ $data['username'] ?? '' }}">
        </div>
        <div class="col-md-6 position-relative">
          <label class="form-label">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="กรุณากรอกรหัสผ่านใหม่">
          <span class="eye-icon" onclick="togglePassword()">👁️</span>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function togglePassword() {
    var passwordField = document.getElementById("password");
    var eyeIcon = document.querySelector(".eye-icon");

    if (passwordField.type === "password") {
      passwordField.type = "text";
      eyeIcon.textContent = "👁️";
    } else {
      passwordField.type = "password";
      eyeIcon.textContent = "👁️";
    }
  }
</script>
@endpush

@endsection
