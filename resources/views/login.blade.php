<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #d3d3d3, #ffffff), #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        @media (min-width: 768px) {
            .content {
                width: 91%;
                height: 84%;
                background-color: #f8f8f8;
                display: grid;
                grid-template-columns: 1fr 1fr;
                justify-content: center;
                align-items: center;
            }
        }

        .objectcontent {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header-section {
            text-align: center;
        }

        .main-title {
            font-size: 4em;
            background: linear-gradient(to right, #7b5be6, #f06292);
            -webkit-background-clip: text;
            color: transparent;
        }

        .subtitle {
            font-size: 1.2em;
            color: #555;
            margin-top: 10px;
        }

        .container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px 75px;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 0.9em;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: linear-gradient(to right, #7b5be6, #f06292);
            border: none;
            color: white;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        .btn:hover {
            background: linear-gradient(to right, #6a4dc6, #e04872);
        }

        .error {
            color: red;
            font-size: 0.9em;
            text-align: center;
            margin-bottom: 15px;
        }

        .forgot-password {
            display: block;
            text-align: center;
            margin-top: 10px;
            font-size: 0.9em;
            color: #555;
            text-decoration: none;
        }

        .forgot-password:hover {
            color: #333;
        }

        #resetPasswordModal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 9999;
        }

        .reset-modal-content {
            max-width: 400px;
            margin: 10% auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }

        .reset-modal-content input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .reset-modal-content button {
            width: 100%;
            background: #4CAF50;
            border: none;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
        }

        .reset-modal-content a {
            display: inline-block;
            margin-top: 15px;
            text-align: center;
            width: 100%;
            color: #777;
        }

    </style>
</head>
<body>
    <div class="content">
        <div class="objectcontent">
            <div class="header-section">
                <h1 class="main-title">ยินดีต้อนรับ</h1>
                <p class="subtitle">เริ่มต้นการใช้งานเพื่อการสื่อสารภายในองค์กรของคุณ</p>
            </div>
        </div>
        <div class="objectcontent">
            <div class="container">
                <h1>เข้าสู่ระบบ</h1>
                @if($errors->any())
                    <p class="error">{{ $errors->first() }}</p>
                @endif
                @if(session('status'))
                    <p class="text-success text-center">{{ session('status') }}</p>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="username">ชื่อผู้ใช้</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group input">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn">เข้าสู่ระบบ</button>
                    <a href="#" class="forgot-password">ลืมรหัสผ่าน?</a>
                </form>
            </div>
        </div>
    </div>

    <!--  ฟอร์มลืมรหัสผ่าน -->
    <div id="resetPasswordModal" style="display:none;">
        <div class="reset-modal-content">
            <h3>ลืมรหัสผ่าน</h3>
            <form method="POST" action="{{ route('password.custom.send') }}">
                @csrf
                <label>กรอกอีเมลที่ใช้สมัคร</label>
                <input type="email" name="email" placeholder="example@email.com" required>
                <button type="submit" class="btn">ส่งลิงก์รีเซ็ตรหัสผ่าน</button>
            </form>
            <a href="#" onclick="document.getElementById('resetPasswordModal').style.display='none'; return false;">ยกเลิก</a>
        </div>
    </div>

    <script>
        document.querySelector('.forgot-password')?.addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('resetPasswordModal').style.display = 'block';
        });
    </script>
</body>
</html>

