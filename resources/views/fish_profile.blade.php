<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --whatsapp-green: #25D366;
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
            padding-bottom: 120px;
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
            display: block;
        }

        .fresh-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            background: rgba(15, 23, 42, 0.88);
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

        .footer-info {
            text-align: center;
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 20px;
        }

        /* شريط العمليات السفلي */
        .action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 12px 16px;
            box-shadow: 0 -5px 25px rgba(0,0,0,0.1);
            display: flex;
            gap: 8px;
            max-width: 500px;
            margin: 0 auto;
            border-top: 1px solid #E2E8F0;
            z-index: 100;
        }

        .btn {
            border: none;
            border-radius: 14px;
            font-weight: 800;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-add-cart {
            flex: 2;
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            padding: 14px 12px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-whatsapp-direct {
            flex: 1.5;
            background: #25D366;
            color: white;
            padding: 14px 10px;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
        }

        .btn-app {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 14px 8px;
            box-shadow: 0 4px 12px rgba(139, 0, 0, 0.3);
            font-size: 12px;
        }

        /* البانر العائم للسلة المجمعة */
        .cart-floating-bar {
            position: fixed;
            bottom: 74px;
            left: 16px;
            right: 16px;
            max-width: 468px;
            margin: 0 auto;
            background: #0F172A;
            color: white;
            border-radius: 16px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            border: 1px solid var(--gold);
            z-index: 99;
            transform: translateY(150%);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .cart-floating-bar.active {
            transform: translateY(0);
        }

        .cart-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-count-badge {
            background: var(--gold);
            color: #0F172A;
            font-weight: 900;
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 10px;
        }

        /* نافذة منبثقة للسؤال: هل تريد طلب صنف آخر؟ */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(4px);
            z-index: 200;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            width: 100%;
            max-width: 500px;
            border-radius: 24px 24px 0 0;
            padding: 24px 20px;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.2);
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }

        .modal-header {
            text-align: center;
            margin-bottom: 16px;
        }

        .modal-header h3 {
            font-size: 18px;
            color: #0F172A;
            font-weight: 800;
        }

        .modal-header p {
            font-size: 13px;
            color: #64748B;
            margin-top: 4px;
        }

        .cart-items-list {
            max-height: 220px;
            overflow-y: auto;
            margin-bottom: 16px;
            border: 1px solid #F1F5F9;
            border-radius: 14px;
            padding: 10px;
            background: #F8FAFC;
        }

        .cart-item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #E2E8F0;
        }

        .cart-item-row:last-child {
            border-bottom: none;
        }

        .modal-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-modal-scan {
            background: #F1F5F9;
            color: #1E293B;
            border: 1px solid #CBD5E1;
            padding: 14px;
            font-weight: 800;
            font-size: 14px;
        }

        .btn-modal-whatsapp {
            background: #25D366;
            color: white;
            padding: 14px;
            font-weight: 800;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
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
                <img src="{{ $displayImage }}" alt="{{ $product->name }}" onerror="this.src='https://images.unsplash.com/photo-1534939561126-855b8675edd7?w=700'">
                <div class="fresh-badge">
                    <span>✨</span>
                    <span>طازج يومياً من البحر</span>
                </div>
            </div>

            <div class="fish-content">
                <div class="fish-header">
                    <div>
                        <h2 class="fish-name" id="currentFishName">{{ $product->name }}</h2>
                        <div class="fish-category">{{ $product->category->name ?? 'مأكولات بحرية' }}</div>
                    </div>
                    <div class="price-badge">
                        <div class="price-amount" id="currentFishPrice">{{ number_format((float) ($product->price ?? 0)) }}</div>
                        <div class="price-currency">ريال يمني</div>
                    </div>
                </div>

                @if(!empty($product->description))
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
            <p>رقم طلبات واستفسارات المطعم المعتمد: <strong>{{ $phone }}</strong></p>
        </div>
    </div>

    <!-- البانر العائم لسلة البسطة -->
    <div class="cart-floating-bar" id="cartFloatingBar">
        <div class="cart-info">
            <span class="cart-count-badge" id="cartCountBadge">1 صنف</span>
            <div style="font-size: 12px;">
                <div style="font-weight: 800;" id="cartTotalText">الإجمالي: 0 ريال</div>
            </div>
        </div>
        <button class="btn" style="background: #25D366; color: white; padding: 6px 14px; font-size: 12px;" onclick="openOrderModal()">
            <span>إرسال للواتساب 💬</span>
        </button>
    </div>

    <!-- شريط الأزرار الرئيسي في الأسفل -->
    <div class="action-bar">
        <button class="btn btn-add-cart" id="addToCartBtn" onclick="addItemToOrder()">
            <span>🛒</span>
            <span>إضافة للطلب</span>
        </button>

        <a href="javascript:void(0)" class="btn btn-whatsapp-direct" onclick="sendSingleWhatsApp()">
            <span>💬</span>
            <span>طلب فوري</span>
        </a>

        <a href="yemenstars://product/{{ $product->id }}" class="btn btn-app" id="openAppBtn">
            <span>📱</span>
            <span>التطبيق</span>
        </a>
    </div>

    <!-- نافذة المودال التفاعلية: هل تريد طلب صنف آخر؟ -->
    <div class="modal-overlay" id="orderModal">
        <div class="modal-content">
            <div class="modal-header">
                <div style="font-size: 32px; margin-bottom: 6px;">🐟🛒</div>
                <h3>قائمة أصناف البسطة المختارة</h3>
                <p>هل تود إضافة صنف سمك آخر، أم إرسال الطلب كاملاً عبر الواتساب الآن؟</p>
            </div>

            <div class="cart-items-list" id="cartItemsList">
                <!-- يتم تعبئتها ديناميكياً بالجافاسكريبت -->
            </div>

            <div style="display: flex; justify-content: space-between; font-weight: 800; font-size: 15px; margin-bottom: 14px; padding: 0 4px;">
                <span>الإجمالي الكلي:</span>
                <span style="color: var(--primary);" id="modalTotalSum">0 ريال</span>
            </div>

            <div class="modal-actions">
                <button class="btn btn-modal-scan" onclick="scanAnotherFish()">
                    <span>➕ نعم، مسح صنف بحري آخر من البسطة</span>
                </button>
                <button class="btn btn-modal-whatsapp" onclick="sendFullWhatsAppOrder()">
                    <span>📲 إرسال الطلب الكامل دفعة واحدة عبر الواتساب</span>
                </button>
                <button class="btn" style="background: transparent; color: #64748B; font-size: 12px; padding: 6px;" onclick="closeOrderModal()">
                    إغلاق ومتابعة التصفح
                </button>
            </div>
        </div>
    </div>

    <script>
        // بيانات الصنف الحالي
        const currentItem = {
            id: "{{ $product->id }}",
            name: "{{ $product->name }}",
            price: {{ (float) ($product->price ?? 0) }},
            qty: 1
        };

        const restaurantWhatsApp = "967771000272"; // الرقم المعتمد بناءً على طلب العميل

        // مفتاح السلة في التخزين المحلي للمتصفح
        const CART_STORAGE_KEY = 'yemenstars_fish_order_cart';

        function getCart() {
            try {
                const raw = localStorage.getItem(CART_STORAGE_KEY);
                return raw ? JSON.parse(raw) : [];
            } catch (e) {
                return [];
            }
        }

        function saveCart(cart) {
            try {
                localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
            } catch (e) {}
            updateFloatingCartBar();
        }

        // إضافة الصنف الحالي للطلب
        function addItemToOrder() {
            let cart = getCart();
            const existingIdx = cart.findIndex(i => String(i.id) === String(currentItem.id));
            if (existingIdx !== -1) {
                cart[existingIdx].qty += 1;
            } else {
                cart.push({...currentItem});
            }
            saveCart(cart);
            openOrderModal();
        }

        function updateFloatingCartBar() {
            const cart = getCart();
            const bar = document.getElementById('cartFloatingBar');
            if (cart.length > 0) {
                let totalCount = 0;
                let totalPrice = 0;
                cart.forEach(item => {
                    totalCount += item.qty;
                    totalPrice += (item.price * item.qty);
                });
                document.getElementById('cartCountBadge').innerText = totalCount + ' صنف';
                document.getElementById('cartTotalText').innerText = 'الإجمالي: ' + totalPrice.toLocaleString() + ' ريال';
                bar.classList.add('active');
            } else {
                bar.classList.remove('active');
            }
        }

        function openOrderModal() {
            const cart = getCart();
            if (cart.length === 0) {
                cart.push({...currentItem});
                saveCart(cart);
            }
            renderModalCartItems();
            document.getElementById('orderModal').classList.add('active');
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.remove('active');
        }

        function renderModalCartItems() {
            const cart = getCart();
            const container = document.getElementById('cartItemsList');
            container.innerHTML = '';
            let total = 0;

            cart.forEach((item, index) => {
                total += (item.price * item.qty);
                const row = document.createElement('div');
                row.className = 'cart-item-row';
                row.innerHTML = `
                    <div style="flex: 2;">
                        <div style="font-weight: 700; font-size: 13px;">${item.name}</div>
                        <div style="font-size: 11px; color: #64748B;">${item.price.toLocaleString()} ريال × ${item.qty}</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <button onclick="changeQty(${index}, -1)" style="width: 26px; height: 26px; border: 1px solid #CBD5E1; background: white; border-radius: 6px; font-weight: bold; cursor: pointer;">-</button>
                        <span style="font-weight: 800; font-size: 13px;">${item.qty}</span>
                        <button onclick="changeQty(${index}, 1)" style="width: 26px; height: 26px; border: 1px solid #CBD5E1; background: white; border-radius: 6px; font-weight: bold; cursor: pointer;">+</button>
                        <button onclick="removeItem(${index})" style="background: none; border: none; color: #EF4444; font-size: 16px; cursor: pointer; margin-right: 4px;">🗑️</button>
                    </div>
                `;
                container.appendChild(row);
            });

            document.getElementById('modalTotalSum').innerText = total.toLocaleString() + ' ريال يمني';
        }

        function changeQty(index, delta) {
            let cart = getCart();
            if (cart[index]) {
                cart[index].qty += delta;
                if (cart[index].qty <= 0) {
                    cart.splice(index, 1);
                }
                saveCart(cart);
                renderModalCartItems();
            }
        }

        function removeItem(index) {
            let cart = getCart();
            cart.splice(index, 1);
            saveCart(cart);
            renderModalCartItems();
            if (cart.length === 0) {
                closeOrderModal();
            }
        }

        // مسح صنف آخر من البسطة
        function scanAnotherFish() {
            closeOrderModal();
            // توجيه لطيف للعميل لمسح الكود التالي بكاميرا الجوال
            alert('يرجى توجيه كاميرا هاتفك إلى كارت الصنف التالي في بسطة الأسماك 🐟✨ وسيتم حفظ هذا الصنف وجمعه مع الصنف الجديد في طلب واحد!');
        }

        // إرسال الطلب الكامل المجمع عبر الواتساب دفعة واحدة
        function sendFullWhatsAppOrder() {
            const cart = getCart();
            if (cart.length === 0) {
                sendSingleWhatsApp();
                return;
            }

            let msg = "السلام عليكم ورحمة الله وبركاته،\n";
            msg += "أود تأكيد طلب من بسطة الأسماك بمطعم نجوم اليمن:\n";
            msg += "---------------------------------\n";

            let total = 0;
            cart.forEach((item, idx) => {
                const subtotal = item.price * item.qty;
                total += subtotal;
                msg += `${idx + 1}. ${item.name} (${item.qty} كجم/نفر) - ${subtotal.toLocaleString()} ريال\n`;
            });

            msg += "---------------------------------\n";
            msg += `💰 الإجمالي النهائي: ${total.toLocaleString()} ريال يمني\n`;
            msg += "📍 طلب مباشر عبر كود بسطة الأسماك\n";
            msg += "يرجى تأكيد الاستلام والبدء في التجهيز. شكراً لكم!";

            // تفريغ السلة بعد إرسال الطلب
            localStorage.removeItem(CART_STORAGE_KEY);
            updateFloatingCartBar();
            closeOrderModal();

            const waUrl = "https://wa.me/" + restaurantWhatsApp + "?text=" + encodeURIComponent(msg);
            window.location.href = waUrl;
        }

        // إرسال صنف مفرد مباشرة للواتساب
        function sendSingleWhatsApp() {
            const price = {{ (float) ($product->price ?? 0) }};
            let msg = "السلام عليكم ورحمة الله وبركاته،\n";
            msg += "أود طلب صنف من بسطة الأسماك:\n";
            msg += "🐟 الصنف: {{ $product->name }}\n";
            msg += "💵 السعر: " + price.toLocaleString() + " ريال يمني\n";
            msg += "يرجى تأكيد الاستلام وتجهيز الطلب طازجاً. شكراً لكم!";

            const waUrl = "https://wa.me/" + restaurantWhatsApp + "?text=" + encodeURIComponent(msg);
            window.location.href = waUrl;
        }

        // الربط الذكي للتطبيق (Deep Link): يفتح التطبيق إذا مثبت، أو يفتح المتجر / صفحة الويب إذا غير مثبت
        document.getElementById('openAppBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const appSchemeUrl = "yemenstars://product/{{ $product->id }}";
            // رابط المتجر السحابي أو صفحة التحميل
            const webStoreUrl = "https://yemenstars-api-production.up.railway.app";

            const start = Date.now();
            window.location.href = appSchemeUrl;

            setTimeout(function() {
                // إذا مرت ثانية ونصف ولم يُفتح التطبيق، نفتح له صفحة الموقع/المتجر
                if (Date.now() - start < 2000) {
                    window.location.href = webStoreUrl;
                }
            }, 1500);
        });

        // تهيئة البانر العائم عند تحميل الصفحة
        window.addEventListener('DOMContentLoaded', () => {
            updateFloatingCartBar();
        });
    </script>
</body>
</html>
