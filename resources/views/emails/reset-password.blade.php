<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รีเซ็ตรหัสผ่าน</title>
</head>
<body>
    <h2>คุณได้ส่งคำขอรีเซ็ตรหัสผ่าน</h2>
    <p>คลิกที่ลิงก์ด้านล่างเพื่อรีเซ็ตรหัสผ่านของคุณ:</p>
    <p>
        <a href="{{ url('password/reset/' . $token) }}">
            รีเซ็ตรหัสผ่านตอนนี้
        </a>
    </p>
    <p>หากคุณไม่ได้ทำรายการนี้ กรุณาเพิกเฉยต่ออีเมลฉบับนี้</p>
</body>
</html>
