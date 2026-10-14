<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم | Pro-Booking SaaS</title>
    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts (Cairo) -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
        }
        .sidebar .nav-link {
            color: #94a3b8;
            padding: 12px 20px;
            margin: 4px 15px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: #2563eb;
        }
        .sidebar .nav-link i {
            margin-left: 10px;
        }
        .stat-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        .top-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- القائمة الجانبية (Sidebar) -->
        <div class="col-md-3 col-lg-2 px-0 sidebar d-none d-md-block">
            <div class="p-3 text-center border-bottom border-secondary">
                <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-calendar-check text-warning me-2"></i>Pro-Booking</h4>
                <small class="text-muted">لوحة الإدارة الرئيسية</small>
            </div>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="fa-solid fa-chart-pie"></i> الرئيسية</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa-solid fa-users"></i> المستخدمين</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa-solid fa-bookmark"></i> الحجوزات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa-solid fa-credit-card"></i> الاشتراكات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa-solid fa-sliders"></i> الإعدادات</a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link text-danger" href="{{ route('bookings.index') }}"><i class="fa-solid fa-arrow-right-from-bracket"></i> العودة للموقع</a>
                </li>
            </ul>
        </div>

        <!-- المحتوى الرئيسي (Main Content) -->
        <div class="col-md-9 col-lg-10 ms-auto px-0">
            
            <!-- الشريط العلوي (Top Navbar) -->
            <nav class="navbar top-navbar px-4 py-3">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h5 class="fw-bold mb-0">لوحة تحكم الأدمن</h5>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-primary-subtle text-primary fw-bold p-2">أدمن النظام</span>
                        <div class="fw-semibold"><i class="fa-solid fa-user-circle fa-lg me-1 text-secondary"></i> {{ Auth::user()->name ?? 'المدير' }}</div>
                    </div>
                </div>
            </nav>

            <!-- محتوى اللوحة -->
            <div class="p-4">

                <!-- بطاقة الترحيب -->
                <div class="alert alert-primary border-0 shadow-sm d-flex justify-content-between align-items-center mb-4 p-3 rounded-3">
                    <div>
                        <h5 class="fw-bold mb-1">أهلاً بك مجدداً، {{ Auth::user()->name ?? 'الأدمن' }}! 👋</h5>
                        <p class="mb-0 text-muted small">هذه هي نظرة عامة على أداء نظام الحجوزات والاشتراكات اليوم.</p>
                    </div>
                    <a href="{{ route('bookings.index') }}" class="btn btn-primary btn-sm rounded-2">إدارة الحجوزات</a>
                </div>

                <!-- بطاقات الإحصائيات -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">إجمالي المستخدمين</span>
                                    <h3 class="fw-bold mb-0 mt-1">128</h3>
                                </div>
                                <div class="stat-icon bg-primary-subtle text-primary">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">الحجوزات النشطة</span>
                                    <h3 class="fw-bold mb-0 mt-1">45</h3>
                                </div>
                                <div class="stat-icon bg-success-subtle text-success">
                                    <i class="fa-solid fa-calendar-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">الاشتراكات المدفوعة</span>
                                    <h3 class="fw-bold mb-0 mt-1">32</h3>
                                </div>
                                <div class="stat-icon bg-warning-subtle text-warning">
                                    <i class="fa-solid fa-crown"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">الإيرادات الحالية</span>
                                    <h3 class="fw-bold mb-0 mt-1">$1,250</h3>
                                </div>
                                <div class="stat-icon bg-info-subtle text-info">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- جدول العمليات الحديثة -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="fa-solid fa-list text-primary me-2"></i>آخر الحجوزات والأنشطة</h6>
                        <button class="btn btn-light btn-sm fw-semibold">عرض الكل</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>اسم العميل</th>
                                    <th>خدمة الحجز</th>
                                    <th>حالة الاشتراك</th>
                                    <th>التاريخ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td class="fw-bold">عبدالله ماهر</td>
                                    <td>حجز باقة Pro SaaS</td>
                                    <td><span class="badge bg-success">نشط (مُفعل)</span></td>
                                    <td>2026-10-14</td>
                                    <td><button class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i></button></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td class="fw-bold">محمد علي</td>
                                    <td>فترة تجريبية (Trial)</td>
                                    <td><span class="badge bg-warning text-dark">تجربة 5 أيام</span></td>
                                    <td>2026-10-13</td>
                                    <td><button class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

</body>
</html>