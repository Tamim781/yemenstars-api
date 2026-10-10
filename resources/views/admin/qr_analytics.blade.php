<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة إحصائيات باركودات الأكريليك - بسطة الأسماك</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Cairo', sans-serif; }
        body { background-color: #0F172A; color: #F8FAFC; padding: 24px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #334155; padding-bottom: 16px; }
        .title { font-size: 20px; font-weight: 900; color: #C59A3F; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: #1E293B; border: 1px solid #334155; padding: 20px; border-radius: 16px; text-align: center; }
        .stat-val { font-size: 32px; font-weight: 900; color: #38BDF8; margin-top: 6px; }
        .stat-val.gold { color: #F59E0B; }
        .stat-val.green { color: #10B981; }
        .stat-label { font-size: 13px; color: #94A3B8; font-weight: 700; }
        .filter-bar { background: #1E293B; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; gap: 12px; align-items: center; }
        .filter-btn { background: #334155; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 700; border: 1px solid transparent; }
        .filter-btn.active { background: #C59A3F; color: #0F172A; font-weight: 900; }
        table { width: 100%; border-collapse: collapse; background: #1E293B; border-radius: 14px; overflow: hidden; }
        th { background: #0B1120; color: #C59A3F; padding: 14px; text-align: right; font-size: 13px; font-weight: 800; border-bottom: 2px solid #334155; }
        td { padding: 14px; border-bottom: 1px solid #334155; font-size: 13px; vertical-align: middle; }
        tr:hover { background: #243248; }
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 800; display: inline-block; }
        .badge-scans { background: #0369A1; color: white; }
        .badge-orders { background: #047857; color: white; }
        .btn-card { background: #C59A3F; color: #0F172A; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 800; font-size: 11px; display: inline-flex; align-items: center; gap: 4px; }
        .btn-card:hover { background: #E5C158; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="title">📊 تقرير تفاعل باركودات الأكريليك لبسطة الأسماك</div>
            <div style="font-size: 12px; color: #94A3B8; margin-top: 4px;">تتبع مباشر لعدد الزيارات ونسبة المبيعات الناتجة عن مسح باركود كل سمكة</div>
        </div>
        <a href="/admin" class="filter-btn">← العودة للوحة الإدارة</a>
    </div>

    <!-- البطاقات الإحصائية -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">إجمالي المسحات والزيارات من البسطة</div>
            <div class="stat-val gold">{{ number_format($totalScans) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">إجمالي الطلبات الناتجة عن الكود</div>
            <div class="stat-val green">{{ number_format($totalQrOrders) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">معدل التحول للشراء (Conversion Rate)</div>
            <div class="stat-val">{{ $avgConversion }}%</div>
        </div>
    </div>

    <!-- شريط الفلترة الذكية -->
    <div class="filter-bar">
        <span style="font-size: 13px; font-weight: 800; color: #CBD5E1;">🔍 فلترة وترتيب الأصناف حسب:</span>
        <a href="?sort=scans_desc" class="filter-btn {{ $sortBy === 'scans_desc' ? 'active' : '' }}">الأكثر مسحاً وزيارة 🔥</a>
        <a href="?sort=orders_desc" class="filter-btn {{ $sortBy === 'orders_desc' ? 'active' : '' }}">الأكثر طلباً ومبيعاً 💰</a>
        <a href="?sort=price_desc" class="filter-btn {{ $sortBy === 'price_desc' ? 'active' : '' }}">الأعلى سعراً</a>
    </div>

    <!-- جدول الأسماك والإحصائيات -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>اسم السمكة</th>
                <th>القسم</th>
                <th>السعر</th>
                <th>عدد الزيارات (المسحات)</th>
                <th>الطلبات الفعلية</th>
                <th>نسبة النجاح</th>
                <th>تصدير كارت الأكريليك</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            @php
                $rate = $p->qr_scans_count > 0 ? round(($p->qr_orders_count / $p->qr_scans_count) * 100, 1) : 0;
            @endphp
            <tr>
                <td>{{ $p->id }}</td>
                <td>
                    <strong style="color: white;">{{ $p->name }}</strong>
                    <div style="font-size: 11px; color: #94A3B8;">{{ Str::limit($p->cooking_recommendations, 45) }}</div>
                </td>
                <td>{{ $p->category->name ?? '-' }}</td>
                <td style="color: #FBBF24; font-weight: 800;">{{ number_format($p->price) }} ر.ي</td>
                <td><span class="badge badge-scans">📱 {{ $p->qr_scans_count }} مسحة</span></td>
                <td><span class="badge badge-orders">🛒 {{ $p->qr_orders_count }} طلب</span></td>
                <td>
                    <strong style="color: {{ $rate >= 30 ? '#34D399' : '#CBD5E1' }};">{{ $rate }}%</strong>
                </td>
                <td>
                    <a href="/admin/products/{{ $p->id }}/qr-card" target="_blank" class="btn-card">
                        <span>🖨️</span>
                        <span>طباعة كارت الأكريليك</span>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
