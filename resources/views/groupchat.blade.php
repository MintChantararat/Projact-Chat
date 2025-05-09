@include('templates.header')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groupchat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            position: relative; /*เพื่อให้เนื้อหา Class=position-absolute ที่อยู่ใน CardDetailsEmployee.blade ยึดอิงจาก .content*/
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
        .uniform-btn {
            width: 120px;
            margin: 3px 0;
            font-size: 14px;
        }
        td .uniform-btn {
        width: 120px;
        margin: 3px 0;
        font-size: 14px;
        vertical-align: middle;
        }
        td form {
            display: inline;
            margin: 0;
            padding: 0;
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
                                <input type="text" id="groupSearchInput" placeholder="ค้นหากลุ่ม..." class="form-control">
                            </div>
                        </div>
                        <div class="head-right">
                            สมาชิก
                        </div>
                    </div>
                    <div class="content-body">
                        <div class="table-wrapper">
                            @include('data-management-table.GroupChatTable', ['groupChats' => $groupChats]) <!-- ตารางแสดงข้อมูลกลุ่ม -->
                        </div>
                    </div>
                    <div class="Footer">
                        <div class="Footer-left">
                            <div class="search-bar">
                            </div>
                        </div>
                        <div class="Footer-right">
                                <!-- ปุ่ม จัดการบัญชี -->
                                <a href="#" id="manage-account-btn" class="btn btn-primary custom-btn" onclick="toggleButtons()">จัดการกลุ่ม</a>

                                <!-- ปุ่ม เพิ่มบัญชีผู้ใช้ และ เสร็จสิ้น (เริ่มต้นซ่อน) -->
                                <div id="account-options" style="display: none;">
                                    <a href="#" id="create-group-btn" class="btn btn-primary custom-btn" onclick="openCreateGroupChat()">เพิ่มกลุ่มผู้ใช้</a>
                                    <a href="#" class="btn custom-btn btn-secondary" onclick="toggleButtons()">เสร็จสิ้น</a>
                                </div>
                                <script>
                                function openCreateGroupChat() {
                                    const createGroupModal = document.getElementById('create-group-chat');
                                    if (createGroupModal) {
                                        createGroupModal.style.display = 'block'; // โชว์การ์ด
                                    }
                                }

                                function closeCreateGroupChat() {
                                    const createGroupModal = document.getElementById('create-group-chat');
                                    if (createGroupModal) {
                                        createGroupModal.style.display = 'none'; // ปิดการ์ด
                                    }
                                }
                                </script>
                        </div>

                        <style>
                            .custom-btn {
                                width: 150px; /* ขนาดปุ่ม */
                                text-align: center;
                                margin: 0 5px; /* ระยะห่างระหว่างปุ่ม */
                                padding: 7px 15px;
                                font-size: 14px;
                            }
                        </style>
                        <script>
                        var isManagingAccounts = false;

                        function toggleButtons() {
                            const manageBtn = document.getElementById("manage-account-btn");
                            const optionsDiv = document.getElementById("account-options");

                            if (manageBtn.style.display === "none") {
                                manageBtn.style.display = "inline-block";
                                optionsDiv.style.display = "none";
                                isManagingAccounts = false;
                            } else {
                                manageBtn.style.display = "none";
                                optionsDiv.style.display = "block";
                                isManagingAccounts = true;
                            }

                            // เรียกฟังก์ชั่นแยก
                            updateGroupChatTableButtons();
                        }
                        function updateGroupChatTableButtons() {
                            if (window.isManagingAccounts) {
                                document.querySelectorAll('.leave-btn').forEach(function(btn) {
                                    btn.style.display = 'none'; // ซ่อนปุ่มออกจากกลุ่ม
                                });
                                document.querySelectorAll('.chat-btn').forEach(function(btn) {
                                    btn.style.display = 'none'; // ซ่อนปุ่มแชท
                                });
                                document.querySelectorAll('.edit-btn').forEach(function(btn) {
                                    btn.style.display = 'inline-block'; // แสดงปุ่มแก้ไขกลุ่ม
                                });
                            } else {
                                document.querySelectorAll('.leave-btn').forEach(function(btn) {
                                    btn.style.display = 'inline-block'; // แสดงปุ่มออกจากกลุ่ม
                                });
                                document.querySelectorAll('.chat-btn').forEach(function(btn) {
                                    btn.style.display = 'inline-block'; // แสดงปุ่มแชท
                                });
                                document.querySelectorAll('.edit-btn').forEach(function(btn) {
                                    btn.style.display = 'none'; // ซ่อนปุ่มแก้ไข
                                });
                            }
                        }
                        </script>
                    </div>
                </div>
                @include('data-management-table.CreateGroupChat', ['userList' => $userList])
                @include('data-management-table.EditGroupChat', ['userList' => $userList])

                @push('scripts')
                <script>
                document.body.addEventListener('click', function(e) {
                        let target = e.target.closest('a');

                        if (!target) return;

                        // ✅ ถ้ากดปุ่ม "แก้ไขกลุ่ม"
                        if (target.classList.contains('edit-btn')) {
                            e.preventDefault();
                            const groupData = JSON.parse(target.getAttribute('data-group'));
                            const groupId = target.getAttribute('data-group-id');
                            showEditGroupCard(groupData, groupId); // ✅ เปิดการ์ดแก้ไขกลุ่ม
                        }
                    });
                </script>
                @endpush

                <!--ค้นหา-->
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const input = document.getElementById('groupSearchInput');
                    const rows = document.querySelectorAll('.group-row');

                    input.addEventListener('input', function () {
                        const keyword = this.value.toLowerCase();
                        rows.forEach(row => {
                            const nameCell = row.querySelector('.group-name');
                            const name = nameCell ? nameCell.textContent.toLowerCase() : '';
                            row.style.display = name.includes(keyword) ? '' : 'none';
                        });
                    });
                });
                </script>
            </div>
        </div>
    </div>
    <script>
    window.userListFromServer = @json($userList);
    </script>
    @stack('scripts')
</body>
</html>