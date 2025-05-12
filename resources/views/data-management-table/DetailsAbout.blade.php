@extends('app')

@section('title', 'เกี่ยวกับ')

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
</style>

@if (session('role') === 'admin')
<form method="POST" action="{{ route('about.update') }}" enctype="multipart/form-data">
@csrf
@endif

<div class="container my-5">
    <div class="d-flex justify-content-center">
        <div class="w-100" style="max-width: 1140px;">
            <div class="row g-4">
                <!-- ซ้าย: รูปภาพ -->
                <div class="col-md-4 text-center">
                    <div class="border rounded p-3 bg-light d-flex justify-content-center align-items-center" 
                        style="aspect-ratio: 1 / 1; width: 100%; max-width: 200px; margin: auto; overflow: hidden;">
                        <img id="profileImagePreview" src="{{ $about['photo'] ?? 'https://via.placeholder.com/200x200.png?text=รูปโปรไฟล์' }}" 
                            alt="รูปโปรไฟล์" 
                            class="rounded"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    @if (session('role') === 'admin')
                    <button type="button" class="btn btn-upload" style="width: 50%; margin-top: 10px;" onclick="document.getElementById('profileImageInput').click();">
                        อัปโหลดรูปภาพ
                    </button>
                    <input type="file" name="photo" id="profileImageInput" class="d-none">
                    @endif
                </div>

                <!-- ขวา: แบบฟอร์ม -->
                <div class="col-md-8">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">ชื่อบริษัท</label>
                        <div class="col-sm-8">
                            @if (session('role') === 'admin')
                            <input type="text" class="form-control" name="company_name" value="{{ $about['company_name'] ?? '' }}">
                            @else
                            <p class="form-control-plaintext">{{ $about['company_name'] ?? '-' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">สร้างเมื่อ</label>
                        <div class="col-sm-8">
                            @if (session('role') === 'admin')
                            <input type="date" class="form-control" name="established_at" value="{{ $about['established_at'] ?? '' }}">
                            @else
                            <p class="form-control-plaintext">{{ $about['established_at'] ?? '-' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">จำนวนสมาชิก</label>
                        <div class="col-sm-8 d-flex align-items-center">
                            <span>{{ $memberCount ?? '0' }} คน</span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">อีเมล</label>
                        <div class="col-sm-8">
                            @if (session('role') === 'admin')
                            <input type="email" class="form-control" name="email" value="{{ $about['email'] ?? '' }}">
                            @else
                            <p class="form-control-plaintext">{{ $about['email'] ?? '-' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">เบอร์ติดต่อ</label>
                        <div class="col-sm-8">
                            @if (session('role') === 'admin')
                            <input type="tel" class="form-control" name="phone" value="{{ $about['phone'] ?? '' }}">
                            @else
                            <p class="form-control-plaintext">{{ $about['phone'] ?? '-' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">ที่อยู่</label>
                        <div class="col-sm-8">
                            @if (session('role') === 'admin')
                            <textarea class="form-control" name="address" rows="3">{{ $about['address'] ?? '' }}</textarea>
                            @else
                            <p class="form-control-plaintext">{{ $about['address'] ?? '-' }}</p>
                            @endif
                        </div>
                    </div>
                </div> <!-- end form col -->
            </div> <!-- end row -->
        </div>
    </div>
</div>

@if (session('role') === 'admin')
</form>
@endif

<script>
document.getElementById('profileImageInput')?.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (evt) {
            document.getElementById('profileImagePreview').src = evt.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
