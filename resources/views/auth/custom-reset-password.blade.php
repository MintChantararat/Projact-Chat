<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ตั้งรหัสผ่านใหม่</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .reset-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="password"], button {
            width: 100%;
            padding: 10px;
            font-size: 1em;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            margin-top: 10px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<div class="reset-container">
    <h2>ตั้งรหัสผ่านใหม่</h2>

    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.custom.reset') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label>รหัสผ่านใหม่</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>ยืนยันรหัสผ่านใหม่</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit">ยืนยันการตั้งรหัสผ่านใหม่</button>
    </form>
</div>
</body>
</html>
