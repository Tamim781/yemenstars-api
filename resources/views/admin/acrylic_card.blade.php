<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>كارت أكريليك - {{ $product->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Cairo', sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            background-color: #1e293b;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .controls {
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
        }

        .btn {
            background: #c59a3f;
            color: #0f172a;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 800;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .btn:hover {
            background: #d4af37;
        }

        .acrylic-card {
            width: 320px;
            height: 440px;
            background: #0f172a;
            color: white;
            border-radius: 20px;
            border: 3px solid #c59a3f;
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 24px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .acrylic-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #c59a3f, #fef08a, #c59a3f);
        }

        .restaurant-title {
            font-size: 13px;
            font-weight: 800;
            color: #c59a3f;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .tagline {
            font-size: 10px;
            color: #94a3b8;
        }

        .fish-title {
            font-size: 22px;
            font-weight: 900;
            color: #ffffff;
            margin: 10px 0 4px 0;
            line-height: 1.2;
        }

        .price-tag {
            background: rgba(197, 154, 63, 0.15);
            border: 1px solid #c59a3f;
            color: #fef08a;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .qr-wrapper {
            background: white;
            padding: 12px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            border: 2px solid #c59a3f;
        }

        .qr-wrapper img {
            width: 170px;
            height: 170px;
            display: block;
        }

        .instructions {
            font-size: 10.5px;
            color: #cbd5e1;
            margin-top: 10px;
            line-height: 1.4;
            padding: 0 6px;
        }

        .instructions span {
            color: #c59a3f;
            font-weight: 700;
        }

        .footer-brand {
            font-size: 9px;
            color: #64748b;
            margin-top: 8px;
            border-top: 1px solid rgba(255,255,255,0.1);
            width: 100%;
            padding-top: 6px;
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                min-height: auto !important;
            }
            .controls {
                display: none !important;
            }
            .acrylic-card {
                box-shadow: none !important;
                margin: 0 auto;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="controls">
        <button class="btn" onclick="window.print()">🖨️ طباعة كارت الأكريليك / حفظ PDF</button>
        <button class="btn" style="background: #334155; color: white;" onclick="window.history.back()">رجوع للوحة الإدارة</button>
    </div>

    <!-- مقاس الكارت المخصص لحوامل الأكريليك في بسطة المطعم -->
    <div class="acrylic-card">
        <div>
            <div class="restaurant-title">{{ $restaurantName }}</div>
            <div class="tagline">بسطة الأسماك الطازجة يومياً</div>
            <div class="fish-title">{{ $product->name }}</div>
            <div class="price-tag">{{ number_format($product->price) }} ريال يمني</div>
        </div>

        <div class="qr-wrapper">
            <img src="{{ $qrImageUrl }}" alt="QR Code">
        </div>

        <div>
            <div class="instructions">
                <span>📱 امسح الكود بكاميرا هاتفك</span><br>
                لاكتشاف الفوائد الصحية وتوصية الشيف لأفضل طريقة طباخة
            </div>
            <div class="footer-brand">
                نجوم اليمن © 2026 - كود دائم
            </div>
        </div>
    </div>

</body>
</html>
