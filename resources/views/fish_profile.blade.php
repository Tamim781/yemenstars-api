<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - {{ $restaurantName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8B0000;
            --primary-dark: #630000;
            --gold: #C9973E;
            --gold-light: #E5C158;
            --bg: #F8F9FA;
            --card-bg: #FFFFFF;
            --text-dark: #1E293B;
            --text-muted: #64748B;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Cairo', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg);
            color: var(--text-dark);
            line-height: 1.6;
            padding-bottom: 90px;
        }

        .header-bar {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
            padding: 16px 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(139, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-bar h1 {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .header-bar p {
            font-size: 11px;
            color: var(--gold-light);
            margin-top: 2px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 16px;
        }

        .fish-card {
            background: var(--card-bg);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            border: 1px solid #F1F5F9;
            margin-bottom: 16px;
        }

        .fish-image-box {
            position: relative;
            width: 100%;
            height: 250px;
            background-color: #F1F5F9;
        }

        .fish-image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .fresh-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            color: var(--gold-light);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            border: 1px solid rgba(201, 151, 62, 0.4);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .fish-content {
            padding: 20px;
        }

        .fish-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .fish-name {
            font-size: 22px;
            font-weight: 900;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .fish-category {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .price-badge {
            background: linear-gradient(135deg, #FFF7ED 0%, #FEF3C7 100%);
            border: 1.5px solid var(--gold);
            padding: 8px 14px;
            border-radius: 16px;
            text-align: center;
            flex-shrink: 0;
        }

        .price-amount {
            font-size: 20px;
            font-weight: 900;
            color: var(--primary);
            line-height: 1;
        }

        .price-currency {
            font-size: 10px;
            font-weight: 700;
            color: #92400E;
            margin-top: 2px;
        }

        .info-section {
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid #F1F5F9;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .section-text {
            font-size: 13px;
            color: #334155;
            background-color: #F8FAFC;
            padding: 12px 14px;
            border-radius: 14px;
            line-height: 1.6;
            border-right: 4px solid var(--gold);
        }

        .chef-recommendation {
            background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
            border: 1px solid #FDE68A;
            border-right: 4px solid #D97706;
            padding: 14px;
            border-radius: 14px;
            margin-top: 14px;
        }

        .chef-title {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 800;
            color: #92400E;
            margin-bottom: 6px;
        }

        .chef-text {
            font-size: 13px;
            color: #78350F;
            font-weight: 600;
        }

        .texture-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
        }

        .texture-tag {
            background-color: #EFF6FF;
            color: #1D4ED8;
            font-size: 11px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid #DBEAFE;
        }

        .action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 12px 20px;
            box-shadow: 0 -5px 25px rgba(0,0,0,0.08);
            display: flex;
            gap: 12px;
            max-width: 500px;
            margin: 0 auto;
            border-top: 1px solid #E2E8F0;
            z-index: 100;
        }

        .btn-app {
            flex: 2;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            text-align: center;
            padding: 14px 16px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(139, 0, 0, 0.3);
        }

        .btn-whatsapp {
            flex: 1;
            background: #25D366;
            color: white;
            text-align: center;
            padding: 14px 10px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .footer-info {
            text-align: center;
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header-bar">
        <h1>{{ $restaurantName }}</h1>
        <p>🐟 بطاقة صنف بسطة الأسماك الطازجة</p>
    </div>

    <div class="container">
        <div class="fish-card">
            <div class="fish-image-box">
                <img src="{{ $product->image_url ?? asset('images/default-fish.jpg') }}" alt="{{ $product->name }}" onerror="this.src='https://images.unsplash.com/photo-1534939561126-855b8675edd7?w=600'">
                <div class="fresh-badge">
                    <span>✨</span>
                    <span>طازج يومياً من البحر</span>
                </div>
            </div>

            <div class="fish-content">
                <div class="fish-header">
                    <div>
                        <h2 class="fish-name">{{ $product->name }}</h2>
                        <div class="fish-category">{{ $product->category->name ?? 'مأكولات بحرية' }}</div>
                    </div>
                    <div class="price-badge">
                        <div class="price-amount">{{ number_format((float) ($product->price ?? 0)) }}</div>
                        <div class="price-currency">ريال يمني</div>
                    </div>
                </div>

                @if($product->description)
                <p style="font-size: 13px; color: #475569; margin-bottom: 12px;">
                    {{ $product->description }}
                </p>
                @endif

                <!-- الفوائد الصحية والقيمة الغذائية -->
                <div class="info-section">
                    <div class="section-title">
                        <span>🌿</span>
                        <span>الفوائد الصحية والقيمة الغذائية</span>
                    </div>
                    <div class="section-text">
                        {{ $product->benefits ?? 'غني بأحماض أوميغا 3 المفيدة لصحة القلب والنشاط الذهني، ومصدر ممتاز للبروتين الطبيعي الخفيف على المعدة والمعادن الضرورية.' }}
                    </div>
                </div>

                <!-- توصية الشيف لأفضل طريقة طباخة -->
                <div class="chef-recommendation">
                    <div class="chef-title">
                        <span>👨‍🍳</span>
                        <span>توصية شيف نجوم اليمن:</span>
                    </div>
                    <div class="chef-text">
                        {{ $product->cooking_recommendations ?? 'ينصح به: موفي بلدي في التنور مع تتبيلتنا الخاصة، أو مشوي على الفحم مع صوص الحمر والسحاوق.' }}
                    </div>
                </div>

                <!-- مواصفات اللحم والشوك -->
                <div class="texture-tags">
                    <div class="texture-tag">🥩 {{ $product->meat_texture ?? 'لحم أبيض ناصع متماسك' }}</div>
                    <div class="texture-tag">🦴 خالي من الشوك الدقيق</div>
                    <div class="texture-tag">👨‍👩‍👧‍👦 مناسب جداً للعائلات والأطفال</div>
                </div>
            </div>
        </div>

        <div class="footer-info">
            <p>تم مسح هذا الكود مباشرة من بسطة الأسماك في صالة المطعم</p>
            <p>جميع الأسماك بلدية وطازجة ومختارة بعناية يومياً</p>
        </div>
    </div>

    <div class="action-bar">
        <a href="yemenstars://product/{{ $product->id }}" class="btn-app" id="openAppBtn">
            <span>📱</span>
            <span>اطلب في التطبيق</span>
        </a>
        <a href="{{ $whatsappUrl }}?text={{ urlencode('السلام عليكم، أود طلب سمك ' . $product->name . ' من بسطة المطعم.') }}" class="btn-whatsapp" target="_blank">
            <span>💬</span>
            <span>واتساب</span>
        </a>
    </div>

    <script>
        // محاولة فتح التطبيق مباشرة في حال كان مثبتاً في الجوال
        document.getElementById('openAppBtn').addEventListener('click', function(e) {
            var appUrl = "yemenstars://product/{{ $product->id }}";
            setTimeout(function() {
                // إذا لم يكن التطبيق مثبتاً، يتم التنبيه
            }, 1000);
        });
    </script>
</body>
</html>
