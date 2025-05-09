<!-- resources/views/layouts/header.blade.php -->
<div class="header">
    <div class="header-left d-flex align-items-center gap-2">
        @if (!empty($headerAbout['photo']))
            <img src="{{ $headerAbout['photo'] }}" alt="Logo" style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%;">
        @endif
        <span class="company-name">
            {{ $headerAbout['company_name'] ?? 'ยังไม่ได้ตั้งชื่อบริษัท' }}
        </span>
    </div>
    <div class="header-right">
        <img src="{{ session('user_avatar') }}" alt="User Avatar" class="user-avatar">
        <span class="user-name">{{ session('user_name', 'ไม่พบข้อมูล ชื่อ-สกุล') }}</span>
    </div>
</div>

<style>
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0px;
    background-color: #ffffff;
    border-bottom: 1px solid #ddd;
    position: fixed; /* Fixed header */
    top: 0;
    left: 0;
    width: 100%; /* Full width */
    z-index: 1000; /* Ensures header is on top */
}
.header-left {
    font-size: 18px;
    font-weight: bold;
    padding-left: 30px;
}
.header-right {
    display: flex;
    align-items: center;
    padding-right: 30px;
}
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}
.user-name {
    font-size: 16px;
}
</style>
