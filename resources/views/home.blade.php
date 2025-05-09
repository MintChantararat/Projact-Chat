@include('templates.header')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script type="module" src="/app.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            flex-direction: column; /* เปลี่ยนเป็น column เพื่อให้ footer อยู่ในตำแหน่งล่าง */
            min-height: 100vh; /* ครอบคลุมทั้งหน้าจอ */
            width: 100vw !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .content {
            position: relative; /*เพื่อให้เนื้อหา Class=position-absolute .content*/
            flex: 1;
            margin-left: 260px;
            margin-top: 60px;
            padding: 50px;
            background-color: #f9f9f9;
            overflow-y: auto; /* เลื่อนเนื้อหาภายในได้ */
        }
        .content2 {
            position: relative; /* เพื่อให้ chat-input อิงจากตรงนี้ */
            border-radius: 10px;  /*ความโค้ง*/
            background-color: #ffffff;
            max-height: 100%;
            width: 100%; /* ขยายเต็มพื้นที่ของ .content */
            min-height: fit-content; /* ปรับขนาดตามเนื้อหา */
            overflow: hidden;    /* ซ่อนเนื้อหาที่เกิน */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
        }
        .content-body { /* เนื้อหาตรงกลาง */
            height: 70vh;
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
        mark {
            background-color: yellow;
            padding: 0 2px;
            border-radius: 3px;
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
                        <div class="head-left">
                            <div class="search-bar">
                                <input type="text" id="search-input" placeholder="ค้นหา..." style="width: 350px;">
                            </div>
                        </div>
                        <div class="head-right">
                            ข่าวประชาสัมพันธ์
                        </div>
                    </div>
                    <div class="content-body">
                        <div class="table-wrapper">
                            @include('data-management-table.DetailsHome')
                        </div>
                    </div>
                    <div class="Footer">
                        <div class="Footer-left" style="visibility: hidden;">
                            123
                            <div class="search-bar">
                            </div>
                        </div>
                        <div class="Footer-right">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');

    searchInput.addEventListener('input', function () {
        const keyword = this.value.trim().toLowerCase();
        const cards = document.querySelectorAll('.container-post .card');

        let firstMatch = null;

        cards.forEach(card => {
            const text = card.innerText.toLowerCase();
            const html = card.innerHTML;

            // ล้าง highlight เดิม
            card.innerHTML = html.replace(/<mark>(.*?)<\/mark>/g, '$1');

            if (keyword && text.includes(keyword)) {
                // highlight คำที่ค้นหา
                card.innerHTML = card.innerHTML.replace(
                    new RegExp(`(${keyword})`, 'gi'),
                    '<mark>$1</mark>'
                );

                // เก็บ match แรก
                if (!firstMatch) {
                    firstMatch = card;
                }
            }
        });

        // เลื่อนสกอไปยังผลลัพธ์แรก
        if (firstMatch) {
            firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>

</html>