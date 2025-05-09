@include('templates.header')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        .container {
            display: flex;
            height: 100vh; /* ครอบคลุมทั้งหน้าจอ */
        }
        /*.content {
            flex: 1; /* ใช้พื้นที่ที่เหลือจากเมนู */
            /*padding: 20px;
            margin: 20px; /* เว้นระยะห่างรอบขอบ */
            /*background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* เงา */
            /*border-radius: 10px; /* มุมโค้งมน */
            /*overflow-y: auto; /* หากเนื้อหาเกิน ให้เลื่อน */
        /*}*/
        .content {
            flex: 1;  /* ใช้พื้นที่ที่เหลือจากเมนู */
            margin-left: 260px; /* ระยะห่างจาก menu */
            margin-top: 60px; /* ระยะห่างจาก header */
            padding: 20px;
            background-color: #f9f9f9;
            min-height: calc(100vh - 60px); /* Full height minus header height */
            overflow: auto;  /* หากเนื้อหาเกิน ให้เลื่อน */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="menu">
            @include('templates.menu') <!-- เมนู -->
        </div>
        <div class="content">
            <h1>Welcome</h1>
            <p>This is the content area. Adjust your content here.</p>
        </div>
    </div>
</body>
</html>