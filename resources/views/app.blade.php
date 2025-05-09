<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!--เรียกใช้งาน bootstrap สำหรับ CSS-->
    </head>

    <body>
        
        <div class = "py-3">
            @yield('content')
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> <!--เรียกใช้งาน bootstrap สำหรับ JS-->

        @stack('scripts') <!-- ✅ เพิ่มบรรทัดนี้เพื่อให้สคริปต์จากหน้าอื่นมาทำงานได้ -->

    </body>
</html>