@include('templates.header')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        .container {
            display: flex;
            min-height: 100vh; /* ครอบคลุมทั้งหน้าจอ */
            width: 100vw !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .content {
            position: relative; /*เพื่อให้เนื้อหา Class=position-absolute ยึดอิงจาก .content*/
            flex: 1;  /* ขยายตัวและใช้พื้นที่ว่างที่เหลือทั้งหมดที่มีในกล่อง */
            margin-left: 260px; /* ระยะห่างจาก menu */
            margin-top: 60px; /* ระยะห่างจาก head */
            padding: 50px; /*ระยะห่างภายใน*/
            background-color: #f9f9f9;
        }
        .content2 {
            border-radius: 10px;  /*ความโค้ง*/
            background-color: #ffffff;
            max-height: 100%;
            width: 100%; /* ขยายเต็มพื้นที่ของ .content */
            overflow: hidden;    /* ซ่อนเนื้อหาที่เกิน */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
        }
        .chat-content-body {
            display: grid;
            grid-template-columns: 300px 1fr; /* Sidebar: 300px, Content: ขยายที่เหลือ */  /* grid แบ่งเนื้อหา */
            height: 74vh; /* ให้ขยายตามเนื้อหา */
        }
        .content2-inchat{
            position: relative; /* เพื่อให้ chat-input อิงจากตรงนี้ */
            padding: 10px; /*ระยะห่างภายใน*/
            display: flex;
            flex-direction: column;
            max-height: 74vh; /* กำหนดเพดานสูงสุด (หรือเท่ากับ chat-content-body) */
            overflow: hidden; /* ป้องกันเลื่อนจาก container นี้ */
        }
        .chat-sidebar {
            grid-template-columns: minmax(250px, 350px) 1fr; /* minmax(250px, 350px) หมายถึง คอลัมน์แรกจะมีขนาดขั้นต่ำ 250px และขนาดสูงสุด 350px, 1fr หมายถึง คอลัมน์ที่สองจะใช้พื้นที่ที่เหลือทั้งหมด (1 fraction) ขยายหรือย่ออัตโนมัติตามพื้นที่ที่มีอยู่*/
            background-color: #ffffff;
            border-right: 1px solid #ddd;
            height: 74vh; /* ลดความสูงลงเพื่อไม่ให้ช่องแชทตกขอบ */
            overflow-y: auto;
            padding: 10px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
        }
        .search-bar {
            margin-bottom: 10px;
        }
        .search-bar input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .chat-group {
            margin-bottom: 20px;
        }
        .chat-group h4 {
            margin-bottom: 10px;
            font-size: 16px;
            color: #6c5ce7;
        }
        .chat-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .chat-item:hover {
            background-color: #f0f0f0;
        }
        .avatar {
            width: 40px;
            height: 40px;
            background-color: #ddd;
            border-radius: 50%;
            margin-right: 10px;
        }
        .chat-details {
            flex: 1;
        }
        .chat-name {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        .chat-preview {
            font-size: 12px;
            color: #555;
            margin: 0;
        }
        .chat-time {
            font-size: 12px;
            color: #aaa;
        }
        .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            border-bottom: 1px solid #ddd;
            top: 0;
            left: 0;
            padding: 10px 0;
            width: 100%; /* ขนาด */
            z-index: 1000; /* ให้ head อยู่ด้านบนสุด */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        .head-left {
            font-size: 18px;
            font-weight: bold;
            padding-left: 30px;
        }
        .head-right {
            display: flex;
            align-items: center;
            padding-right: 30px;
        }
        .people-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .people-name {
            font-size: 16px;
        }
        .icon-large {
            font-size: 35px;   /* ขนาดไอคอน */
            color:rgb(58, 58, 58);
            margin-right: 25px; /* ระยะห่างจากรูป */
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="menu">
            @include('templates.menu') <!-- เมนู -->
        </div>
            <div class="content">
                <div class="content2">
                    <div class="head">
                        <div class="head-left" style="visibility: hidden;">
                            <div class="search-bar">
                                    <input type="text" placeholder="ค้นหา..."> <!-- ต้องทำให้ค้นหาได้จริง -->
                            </div>
                        </div>
                        <div class="head-right">
                            @isset($receiver)
                                <div class="search-bar" style="margin-right: 25px;">
                                    <input type="text" id="messageSearchInput" placeholder="ค้นหาข้อความ..." style="width: 250px;">
                                </div>
                                <i class="bi bi-images icon-large" id="openGalleryBtn"></i>
                                <img src="{{ $receiver['profile_photo'] ?? asset('default.png') }}" alt="User Avatar" class="user-avatar">
                                <span class="user-name">{{ $receiver['first_name'] ?? '-' }} {{ $receiver['last_name'] ?? '' }}</span>
                            @elseif(isset($group))
                                <div class="search-bar" style="margin-right: 25px;">
                                    <input type="text" id="messageSearchInput" placeholder="ค้นหาข้อความ..." style="width: 250px;">
                                </div>
                                <i class="bi bi-images icon-large" id="openGalleryBtn"></i>
                                <img src="{{ $group['conversation_photo'] ?? asset('default-group.png') }}" alt="Group Avatar" class="user-avatar">
                                <span class="user-name">{{ $group['conversation_name'] ?? '-' }}</span>
                            @endisset
                        </div>
                    </div>
                    <div class="chat-content-body">
                        <div class="chat-sidebar">
                            @include('chat.chat-list')
                        </div>
                        <div class="content2-inchat">
                            @include('chat.chat-conversation', ['groupId' => $groupId ?? null, 'group' => $group ?? null, 'messages' => $messages ?? []])
                        </div>
                    </div>
                </div>
                @include('chat.CardMessagesPhoto')
            </div>
        </div>
    </div>
</body>
</html>