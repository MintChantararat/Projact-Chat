<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #d3d3d3, #ffffff), #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        @media (min-width: 768px){
            .content {
                width: 91%;
                height: 84%;
                background-color: #f8f8f8;
                display: flex;  /* เปลี่ยนจาก grid เป็น flex */
                grid-template-columns: 1fr 1fr;
                justify-content: center;
                align-items: center;
            }
        }
        .container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);  /*เงา*/
            padding: 50px 150px;
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        .objectcontent {
            display: flex;
            flex-direction: center;
            align-items: center;
        }
        h1 {
            font-size: 2.5em;
            background: linear-gradient(to right, #5b69e6, #f06292);
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 10px;
        }
        p {
            color: #666;
            font-size: 1em;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .btn-group {
            display: flex;
            justify-content: space-between;
        }
        .btn {
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            flex: 1;
            margin: 0 5px;
            color: white;
        }
        .btn-back {background: #6c85f0;}
        .btn-back:hover {background: #5669d8;}
        .btn-submit {background: #28a745;}
        .btn-submit:hover {background: #218838;}
    </style>

</head>
<body>
    <div class="content">
        <div class="objectcontent">
            <div class="container">
                <h1>กรุณาป้อนอีเมลของคุณ</h1>
                <p style= "margin-bottom: 40px;">กรุณาป้อนที่อยู่อีเมลของคุณที่ได้ทำการลงทะเบียนใช้งานไว้<br>เพื่อที่เราจะส่งรหัสผ่านในการลงชื่อเข้าใช้ไปให้กับคุณ</p>
                <div class="form-group" style="margin-top: 30px; margin-bottom: 40px;">
                    <input type="email" placeholder="ป้อนอีเมลของคุณ" required>
                </div>
                <div class="btn-group" style="margin-top: 30px; margin-bottom: 10px;">
                    <button class="btn btn-back">ย้อนกลับ</button>
                    <button class="btn btn-submit">ส่ง</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>