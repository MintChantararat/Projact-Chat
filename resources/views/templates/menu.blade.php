<!-- resources/views/layouts/menu.blade.php -->
<div class="menu">
    <ul>
        <li><a href="{{ url('/home') }}" class="{{ request()->is('home') ? 'active' : '' }}">ข่าวสารประชาสัมพันธ์</a></li>
        <li><a href="{{ url('/chat') }}" class="{{ request()->is('chat') ? 'active' : '' }}">ข้อความ</a></li>
        <li><a href="{{ url('/employee') }}" class="{{ request()->is('employee') ? 'active' : '' }}">สมาชิก</a></li>
        <li><a href="{{ url('/profile') }}" class="{{ request()->is('profile') ? 'active' : '' }}">จัดการข้อมูลส่วนตัว</a></li>
        <li><a href="{{ url('/groupchat') }}" class="{{ request()->is('groupchat') ? 'active' : '' }}">จัดการกลุ่มผู้ใช้งาน</a></li>
        <li><a href="{{ url('/about') }}" class="{{ request()->is('about') ? 'active' : '' }}">เกี่ยวกับ</a></li>
        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ออกจากระบบ</a></li>
    </ul>
</div>

<!-- ฟอร์มสำหรับ logout -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
.menu {
    width: 260px;
    background-color: #ffffff;
    padding: 10px 0;
    height: 100vh; /* Full height of the viewport */
    border-right: 1px solid #ddd;
    position: fixed; /* Fixed menu */
    top: 60px; /* Adjust for the height of the header */
    left: 0;
    overflow-y: auto; /* Scrollable if content exceeds viewport height */
    z-index: 999; /* Place below the header */
}
.menu ul {
    list-style: none;
    padding: 5;
    margin: 0;
}
.menu li {
    padding: 5px 5px; /*ระยะห่าง แนวตั้ง แนวนอน*/
}
.menu li a {
    text-decoration: none;
    color: #333;
    font-size: 16px;
    display: block;
    padding: 10px 15px;
}
.menu li a:hover {
    background: #f1effd; 
    border-radius: 7px;
    padding: 10px 15px;
}
.menu li a.active {
    background-color: #e0dcfb;
    border-radius: 7px;
    font-weight: bold;
}
</style>
