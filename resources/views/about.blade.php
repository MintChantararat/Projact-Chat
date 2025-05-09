@include('templates.header')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <script type="module" src="/app.js"></script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        .container {
            display: flex;
            flex-direction: column; /* เปลี่ยนเป็น column เพื่อให้ footer อยู่ในตำแหน่งล่าง */
            min-height: 100vh; /* ครอบคลุมทั้งหน้าจอ */
            width: 100vw !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .content {
            flex: 1;
            margin-left: 260px;
            margin-top: 60px;
            padding: 50px;
            background-color: #f9f9f9;
            overflow-y: auto; /* เลื่อนเนื้อหาภายในได้ */
        }
        .content2 {
            border-radius: 10px;  /*ความโค้ง*/
            background-color: #ffffff;
            max-height: 100%;
            width: 100%; /* ขยายเต็มพื้นที่ของ .content */
            min-height: fit-content; /* ปรับขนาดตามเนื้อหา */
            overflow: hidden;    /* ซ่อนเนื้อหาที่เกิน */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
        }
        .content-body { /* เนื้อหาตรงกลาง */
            height: 75vh;
            width: 100%;
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
            width: 100%; /* Full width */
            z-index: 1000; /* Ensures header is on top */
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
        .Footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            border-top: 1px solid #ddd;
            padding: 10px 0;
            width: 100%;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.05);
            position: sticky; /* ทำให้ Footer ค้างอยู่ที่ด้านล่าง */
            bottom: 0; /* อยู่ติดขอบล่างของ content */
            /*z-index: 1000; ให้อยู่บนสุด */
            z-index: 1;
        }
        .Footer-left {
            font-size: 18px;
            font-weight: bold;
            padding-left: 30px;
        }
        .Footer-right {
            display: flex;
            align-items: center;
            padding-right: 30px;
        }
        .search-bar {}
        .search-bar input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .table-wrapper {
            width: 100%;
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
                            ข้อมูลบริษัท
                        </div>
                    </div>
                    <div class="content-body">
                        <div class="table-wrapper">
                            <!-- ฟอร์มสำหรับอัปเดท -->
                            <form id="aboutForm" method="POST" action="{{ route('about.update') }}" enctype="multipart/form-data">
                                @csrf
                                @include('data-management-table.DetailsAbout')

                                <div class="Footer">
                                    <div class="Footer-left" style="visibility: hidden;">123</div>
                                    <div class="Footer-right">
                                        @if (session('role') === 'admin')
                                            <button type="button" class="btn btn-primary custom-btn" onclick="document.getElementById('aboutForm').submit();">
                                                บันทึก
                                            </button>
                                        @endif
                                        <style>
                                            .custom-btn {
                                                width: 150px;
                                                text-align: center;
                                                margin: 0 5px;
                                                padding: 7px 15px;
                                                font-size: 14px;
                                            }
                                        </style>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>