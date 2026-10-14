<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام الحجوزات الذكي</title>

    <!-- تحميل مكتبة Bootstrap للتنسيق -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- تحميل الأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- تنسيقات إضافية لجعل الواجهة أجمل -->
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .btn-primary { background-color: #0d6efd; border: none; }
        .table thead { background-color: #f1f3f5; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">نظام الحجوزات</a>
            <div class="navbar-nav ms-auto">
                @auth
                    <span class="nav-link text-white">مرحباً، {{ auth()->user()->name }}</span>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        <!-- هذا الجزء هو الذي يستقبل محتوى صفحة الحجوزات -->
        @yield('content')
    </main>

    <!-- تحميل برمجيات Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>