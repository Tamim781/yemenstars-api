<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة مطعم نجوم اليمن</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f8fafc; color: #1e293b; }
    </style>
</head>
    <body class="min-h-screen flex flex-col bg-slate-100">

    <aside id="admin-sidebar" class="hidden md:flex fixed inset-y-0 right-0 z-40 w-64 flex-col bg-[#111827] text-white shadow-2xl">
        <div class="flex items-center gap-3 border-b border-white/10 px-5 py-6">
            <div class="h-12 w-12 overflow-hidden rounded-2xl border border-red-400/50 bg-white shadow-lg">
                <img src="{{ asset('storage/images/logo.png') }}" alt="شعار نجوم اليمن" class="h-full w-full object-cover">
            </div>
            <div class="min-w-0"><p class="truncate text-sm font-bold">نجوم اليمن</p><p class="text-[11px] text-slate-400">لوحة الإدارة</p></div>
            <button type="button" class="mr-auto inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 text-lg md:hidden" onclick="document.getElementById('admin-sidebar').classList.add('hidden')" aria-label="إغلاق القائمة">×</button>
        </div>
        <nav class="flex-1 space-y-1 px-3 py-5 text-sm font-semibold">
            <a href="{{ route('admin.dashboard', ['section' => 'dashboard']) }}" class="flex items-center gap-3 rounded-xl {{ $activeSection === 'dashboard' ? 'bg-red-600 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} px-4 py-3">⌂ <span>الرئيسية والإحصائيات</span></a>
            <a href="{{ route('admin.dashboard', ['section' => 'orders']) }}" class="flex items-center gap-3 rounded-xl {{ $activeSection === 'orders' ? 'bg-red-600 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} px-4 py-3">▣ <span>إدارة الطلبات</span></a>
            <a href="{{ route('admin.dashboard', ['section' => 'products']) }}" class="flex items-center gap-3 rounded-xl {{ $activeSection === 'products' ? 'bg-red-600 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} px-4 py-3">◈ <span>إدارة المنتجات والمنيو</span></a>
            <a href="{{ route('admin.dashboard', ['section' => 'categories']) }}" class="flex items-center gap-3 rounded-xl {{ $activeSection === 'categories' ? 'bg-red-600 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} px-4 py-3">▤ <span>إدارة الأقسام</span></a>
            <a href="{{ route('admin.dashboard', ['section' => 'offers']) }}" class="flex items-center gap-3 rounded-xl {{ $activeSection === 'offers' ? 'bg-red-600 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} px-4 py-3">▥ <span>إدارة البنرات والعروض</span></a>
            <a href="{{ route('admin.dashboard', ['section' => 'account']) }}" class="flex items-center gap-3 rounded-xl {{ $activeSection === 'account' ? 'bg-red-600 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} px-4 py-3">◎ <span>معلومات الحساب</span></a>
            <a href="{{ route('admin.dashboard', ['section' => 'settings']) }}" class="flex items-center gap-3 rounded-xl {{ $activeSection === 'settings' ? 'bg-red-600 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} px-4 py-3">⚙ <span>إعدادات المطعم</span></a>
        </nav>
        <div class="border-t border-white/10 px-5 py-5 text-xs text-slate-400">تحديث يومي للمنيو والطلبات</div>
    </aside>

    <!-- شريط علوي أنيق بنفس تصميمك الأصلي مع زر النشر والشعار -->
    <header class="bg-[#0f172a] text-white shadow-md px-4 sm:px-6 py-3 sm:py-4 md:pr-72 flex justify-between items-center gap-3">
        <button type="button" class="md:hidden inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-xl hover:bg-white/20" onclick="document.getElementById('admin-sidebar').classList.toggle('hidden')" aria-label="فتح القائمة الجانبية">☰</button>
        <div class="flex min-w-0 items-center space-x-3 space-x-reverse">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-white shadow-md border border-amber-400 overflow-hidden">
                <img src="{{ asset('storage/images/logo.png') }}" alt="شعار مطعم نجوم اليمن" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0">
                <h1 class="truncate text-sm sm:text-base font-bold">نظام إدارة مطعم نجوم اليمن</h1>
                <p class="text-xs text-slate-400">Laravel Admin Platform</p>
            </div>
        </div>
        <div class="hidden sm:flex items-center space-x-3 space-x-reverse">
            <!-- زر النشر الحقيقي الذي ظهر في صورتك -->
            <button onclick="alert('تم تجهيز وتثبيت خادم النشر بنجاح!')" class="bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 px-4 py-2 rounded-xl text-xs font-bold shadow-lg hover:from-amber-400 hover:to-amber-500 transition flex items-center space-x-1.5 space-x-reverse">
                <span>🚀</span>
                <span>نشر الموقع</span>
            </button>
            <span class="text-xs bg-red-900/50 border border-red-700 text-red-200 px-3 py-1.5 rounded-xl font-bold">مدير النظام</span>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="rounded-xl border border-white/20 px-3 py-1.5 text-xs font-bold text-white hover:bg-white/10">خروج</button>
            </form>
        </div>
    </header>

    <!-- المحتوى الرئيسي -->
    <main class="flex-1 p-6 md:mr-64 max-w-7xl mx-auto w-full space-y-8">
        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-2xl shadow-sm text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-2xl shadow-sm text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($activeSection === 'dashboard')
        <!-- حالة النظام وأزرار التحكم السريعة -->
        <div id="overview" class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center space-x-3 space-x-reverse">
                <div class="w-3.5 h-3.5 bg-emerald-500 rounded-full animate-pulse"></div>
                <span class="text-sm font-bold text-slate-700">النظام متصل وجاهز للطلبات الحية</span>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                <span class="text-xs text-slate-500">التنبيه الصوتي</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                </label>
            </div>
        </div>

        @endif

        @if(in_array($activeSection, ['dashboard', 'products'], true))
        <!-- قسم إدارة وجبات المنيو -->
        <div id="menu" class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm space-y-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">إدارة وجبات المنيو</h2>
                    <p class="text-xs text-slate-500 mt-1">إضافة وتعديل الأطباق والأسعار وتحديد حالة التوفر لكل قسم</p>
                </div>
                <button onclick="toggleAddProductForm()" class="bg-red-600 text-white px-5 py-2.5 rounded-2xl text-sm font-bold hover:bg-red-700 shadow-lg shadow-red-600/20 transition flex items-center space-x-2 space-x-reverse">
                    <span>+ إضافة وجبة جديدة</span>
                </button>
            </div>

            <div class="rounded-2xl border border-red-100 bg-red-50 p-4">
                <div class="mb-3"><h3 class="text-sm font-bold text-red-900">إضافة قسم جديد</h3><p class="mt-1 text-[11px] text-red-800/70">يمكنك إضافة اسم القسم وصورته ليظهر كأيقونة في تطبيق Flutter.</p></div>
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-3 md:grid-cols-3">
                    @csrf
                    <input name="name" required placeholder="اسم القسم" class="rounded-xl border border-red-200 bg-white px-3 py-2 text-sm">
                    <input name="sort_order" type="number" min="0" placeholder="ترتيب العرض" class="rounded-xl border border-red-200 bg-white px-3 py-2 text-sm">
                    <input name="image" type="file" accept="image/jpeg,image/png,image/webp" class="rounded-xl border border-red-200 bg-white px-3 py-2 text-sm">
                    <button class="rounded-xl bg-red-600 px-4 py-2 text-sm font-bold text-white hover:bg-red-700 md:col-span-3">حفظ القسم والصورة</button>
                </form>
            </div>

            <!-- أزرار الأقسام التفاعلية (مطابقة تماماً لما ظهر في صورتك) -->
            <div class="bg-slate-50 border border-slate-200 p-5 rounded-2xl">
                <p class="text-xs font-bold text-slate-500 mb-3">اختر القسم لاستعراض الأصناف المخزنة:</p>
                <div class="flex flex-wrap gap-2.5">
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold transition {{ !$selectedCategoryId ? 'bg-red-600 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100' }}">
                        جميع الأقسام ({{ $productsCount ?? 0 }})
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('admin.dashboard', ['category_id' => $cat->id]) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold transition {{ $selectedCategoryId == $cat->id ? 'bg-red-600 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100' }}">
                            {{ $cat->name }} ({{ $cat->products_count ?? 0 }})
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- نموذج إضافة وجبة (يفتح عند الضغط على الزر) -->
            <div id="addProductFormContainer" class="hidden bg-slate-50 border border-slate-200 p-6 rounded-2xl">
                <h3 class="text-sm font-bold text-slate-900 mb-4">+ إضافة وجبة جديدة لقاعدة البيانات</h3>
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">القسم</label>
                        <select name="category_id" required class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-sm">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $selectedCategoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">اسم الوجبة</label>
                        <input type="text" name="name" required class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">السعر (ريال)</label>
                        <input type="number" step="0.01" name="price" required class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">صورة المنتج من الجهاز</label>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-sm">
                        <p class="text-[11px] text-slate-400 mt-1">JPG أو PNG أو WebP — الحد الأقصى 5MB</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">أو رابط الصورة (اختياري)</label>
                        <input type="url" name="image_url" placeholder="https://..." class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-sm">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">الوصف</label>
                        <input type="text" name="description" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-sm">
                    </div>
                    <div class="flex items-center space-x-6 space-x-reverse sm:col-span-3 pt-2">
                        <label class="flex items-center space-x-2 space-x-reverse text-xs text-slate-700 cursor-pointer">
                            <input type="checkbox" name="is_available" value="1" checked class="rounded border-slate-300 text-red-600">
                            <span>متوفر للطلب</span>
                        </label>
                        <label class="flex items-center space-x-2 space-x-reverse text-xs text-slate-700 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" class="rounded border-slate-300 text-amber-500">
                            <span>صنف مميز</span>
                        </label>
                        <label class="flex items-center space-x-2 space-x-reverse text-xs text-slate-700 cursor-pointer">
                            <input type="checkbox" name="is_daily_special" value="1" class="rounded border-slate-300 text-indigo-500">
                            <span>طبق متغير يومياً</span>
                        </label>
                    </div>
                    <div class="sm:col-span-3 flex gap-3 pt-2">
                        <button type="submit" class="flex-1 bg-red-600 text-white font-bold py-2.5 rounded-xl hover:bg-red-700 transition text-sm">حفظ الوجبة</button>
                        <button type="button" onclick="toggleAddProductForm()" class="bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-bold">إلغاء</button>
                    </div>
                </form>
            </div>

            <!-- عرض عنوان القسم المختار وعدد أطباقه (مطابق ل صورتك تماماً) -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 pt-2">
                <div class="flex items-center space-x-2 space-x-reverse">
                    <div class="w-2.5 h-2.5 bg-red-600 rounded-full"></div>
                    <h3 class="font-bold text-slate-800 text-base">
                        @if($selectedCategoryId)
                            {{ $categories->firstWhere('id', $selectedCategoryId)->name ?? 'القسم المختار' }}
                        @else
                            جميع الأقسام
                        @endif
                    </h3>
                </div>
                <span class="text-xs bg-slate-100 text-slate-600 font-bold px-3 py-1 rounded-full">
                    {{ count($products) }} أطباق
                </span>
            </div>

            <!-- قائمة البطاقات الفردية للأصناف (مطابقة لبطاقة "مرجان" في صورتك) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($products as $product)
                    <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between relative overflow-hidden">
                        <div class="absolute top-4 right-4 flex gap-2">
                        @if($product->is_featured)
                            <div class="bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm flex items-center space-x-1 space-x-reverse">
                                <span>⭐ مميز</span>
                            </div>
                        @endif
                        @if($product->is_daily_special)
                            <div class="bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                متغير يومياً 🔄
                            </div>
                        @endif
                        </div>

                        <!-- صورة الوجبة -->
                        <div class="h-44 w-full bg-slate-100 rounded-2xl overflow-hidden mb-4 flex items-center justify-center">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-slate-400 text-4xl">🍽️</div>
                            @endif
                        </div>

                        <!-- تفاصيل الوجبة -->
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-start">
                                <h4 class="text-lg font-bold text-slate-900">{{ $product->name }}</h4>
                                <span class="text-lg font-bold text-red-700">{{ number_format($product->price, 2) }} ريال</span>
                            </div>
                            <p class="text-xs text-slate-500 line-clamp-2">
                                {{ $product->description ?? 'لا يوجد وصف متاح لهذا الصنف في قاعدة البيانات.' }}
                            </p>
                        </div>

                        <!-- أزرار التحكم وحالة التوفر (حذف وتعديل وحالة متوفر) -->
                        <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <!-- زر الحذف -->
                                <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذا الطبق؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition">
                                        🗑️
                                    </button>
                                </form>
                                <!-- زر التعديل الحقيقي -->
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">
                                    ✏️
                                </a>
                            </div>

                            <!-- حالة التوفر -->
                            <form action="{{ route('admin.products.availability', $product->id) }}" method="POST" class="flex items-center space-x-2 space-x-reverse">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_available" value="{{ $product->is_available ? 0 : 1 }}">
                                <button type="submit" class="w-10 h-6 rounded-full relative {{ $product->is_available ? 'bg-red-600' : 'bg-slate-300' }}" title="تغيير حالة التوفر">
                                    <span class="absolute top-1 w-4 h-4 rounded-full bg-white transition {{ $product->is_available ? 'left-1' : 'right-1' }}"></span>
                                </button>
                                <span class="text-xs font-bold {{ $product->is_available ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $product->is_available ? 'متوفر' : 'غير متوفر' }}
                                </span>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 py-16 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <p class="text-sm">لا توجد أطباق مخزنة في هذا القسم حتى الآن.</p>
                        <button onclick="toggleAddProductForm()" class="mt-3 text-xs text-red-600 font-bold underline">أضف أول صنف الآن</button>
                    </div>
                @endforelse
            </div>
        </div>

        @endif

        @if(in_array($activeSection, ['dashboard', 'orders'], true))
        <section id="orders" class="scroll-mt-6 bg-white border border-slate-200 p-6 rounded-3xl shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <div><h2 class="text-xl font-bold text-slate-900">آخر الطلبات</h2><p class="mt-1 text-xs text-slate-500">متابعة حالة الطلبات من لوحة واحدة.</p></div>
                <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">{{ $ordersCount ?? 0 }} طلب</span>
            </div>
            <div class="space-y-3">
                @forelse($orders->take(6) as $order)
                    <div class="flex flex-col gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-bold text-slate-900">طلب #{{ $order->id }} — {{ $order->customer_name ?? 'عميل' }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $order->customer_phone ?? '' }} · {{ number_format((float) $order->total_amount, 0) }} ريال</p>
                            <p class="mt-1 text-xs text-slate-600">الدفع: <strong>{{ $order->payment_method ?? 'غير محدد' }}</strong> · حالة الدفع: <strong>{{ $order->payment_status ?? 'pending' }}</strong></p>
                            @if($order->transfer_reference)
                                <p class="mt-1 text-xs text-blue-700">رقم العملية: {{ $order->transfer_reference }}</p>
                            @endif
                            @if($order->notes)
                                <p class="mt-1 rounded-lg bg-amber-50 px-2 py-1 text-xs text-amber-800">ملاحظات العميل: {{ $order->notes }}</p>
                            @endif
                        </div>
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex items-center gap-2">@csrf @method('PUT')<select name="status" onchange="this.form.submit()" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs"><option value="new" {{ $order->status === 'new' ? 'selected' : '' }}>جديد</option><option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>قيد التحضير</option><option value="delivering" {{ $order->status === 'delivering' ? 'selected' : '' }}>قيد التوصيل</option><option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>مكتمل</option><option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>ملغي</option></select></form>
                    </div>
                @empty
                    <p class="rounded-2xl bg-slate-50 p-8 text-center text-sm text-slate-400">لا توجد طلبات حتى الآن.</p>
                @endforelse
            </div>
        </section>

        @endif

        @if($activeSection === 'categories')
        <section id="categories" class="scroll-mt-6 bg-white border border-slate-200 p-6 rounded-3xl shadow-sm">
            <div class="mb-5"><h2 class="text-xl font-bold text-slate-900">إدارة الأقسام</h2><p class="mt-1 text-xs text-slate-500">إضافة الأقسام وترتيبها وتجهيز صورها لتطبيق Flutter.</p></div>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="mb-6 grid grid-cols-1 gap-4 rounded-2xl border border-red-100 bg-red-50 p-5 md:grid-cols-3">
                @csrf
                <input name="name" required placeholder="اسم القسم" class="rounded-xl border border-red-200 bg-white px-3 py-2.5 text-sm">
                <input name="sort_order" type="number" min="0" placeholder="ترتيب العرض" class="rounded-xl border border-red-200 bg-white px-3 py-2.5 text-sm">
                <input name="image" type="file" accept="image/jpeg,image/png,image/webp" class="rounded-xl border border-red-200 bg-white px-3 py-2.5 text-sm">
                <button class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-red-700 md:col-span-3">إضافة القسم</button>
            </form>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @forelse($categories as $cat)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl bg-white">
                                @if($cat->image_url)
                                    <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center text-2xl text-red-700">◈</div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="truncate font-bold text-slate-900">{{ $cat->name }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $cat->products_count }} منتجات · ترتيب {{ $cat->sort_order }}</p>
                            </div>
                        </div>
                        <details class="mt-4">
                            <summary class="cursor-pointer text-xs font-bold text-blue-700">تعديل القسم والصورة</summary>
                            <form action="{{ route('admin.categories.update', $cat->id) }}" method="POST" enctype="multipart/form-data" class="mt-3 space-y-2">
                                @csrf
                                @method('PUT')
                                <input name="name" value="{{ $cat->name }}" required class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs">
                                <input name="sort_order" type="number" min="0" value="{{ $cat->sort_order }}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs">
                                <input name="image" type="file" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs">
                                <button class="w-full rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white hover:bg-blue-700">حفظ التعديل</button>
                            </form>
                        </details>
                        <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" class="mt-3" onsubmit="return confirm('هل تريد حذف هذا القسم؟ يجب نقل أو حذف منتجاته أولاً.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full rounded-xl bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100">حذف القسم</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">لا توجد أقسام بعد.</p>
                @endforelse
            </div>
        </section>
        @endif

        @if($activeSection === 'offers')
        <section id="offers" class="scroll-mt-6 bg-white border border-slate-200 p-6 rounded-3xl shadow-sm">
            <div class="mb-5"><h2 class="text-xl font-bold text-slate-900">إدارة البنرات والعروض</h2><p class="mt-1 text-xs text-slate-500">أضف صور الإعلانات التي تظهر متحركة في أعلى تطبيق Flutter.</p></div>
            <form action="{{ route('admin.offers.store') }}" method="POST" enctype="multipart/form-data" class="mb-6 grid grid-cols-1 gap-3 rounded-2xl border border-red-100 bg-red-50 p-5 md:grid-cols-2">
                @csrf
                <input name="title" required placeholder="عنوان البنر، مثل: عرض التوصيل" class="rounded-xl border border-red-200 bg-white px-3 py-2.5 text-sm">
                <input name="sort_order" type="number" min="0" value="0" placeholder="ترتيب العرض" class="rounded-xl border border-red-200 bg-white px-3 py-2.5 text-sm">
                <input name="image" type="file" accept="image/jpeg,image/png,image/webp" class="rounded-xl border border-red-200 bg-white px-3 py-2.5 text-sm">
                <input name="image_url" type="url" placeholder="أو رابط صورة https://..." class="rounded-xl border border-red-200 bg-white px-3 py-2.5 text-sm">
                <textarea name="description" rows="2" placeholder="وصف مختصر للإعلان" class="rounded-xl border border-red-200 bg-white px-3 py-2.5 text-sm md:col-span-2"></textarea>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" checked> عرض البنر في التطبيق</label>
                <button type="submit" class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-red-700">إضافة البنر</button>
            </form>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @forelse($offers as $offer)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="mb-3 h-36 overflow-hidden rounded-xl bg-slate-200">
                            @if($offer->resolved_image_url)<img src="{{ $offer->resolved_image_url }}" alt="{{ $offer->title }}" class="h-full w-full object-cover">@else<div class="flex h-full items-center justify-center text-sm text-slate-400">لا توجد صورة</div>@endif
                        </div>
                        <p class="font-bold text-slate-900">{{ $offer->title }}</p>
                        <p class="mt-1 text-xs text-slate-500">ترتيب {{ $offer->sort_order }} · {{ $offer->is_active ? 'فعال' : 'مخفي' }}</p>
                        <details class="mt-3"><summary class="cursor-pointer text-xs font-bold text-blue-700">تعديل البنر</summary>
                            <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST" enctype="multipart/form-data" class="mt-3 space-y-2">@csrf @method('PUT')
                                <input name="title" value="{{ $offer->title }}" required class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs">
                                <input name="sort_order" type="number" min="0" value="{{ $offer->sort_order }}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs">
                                <input name="image" type="file" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs">
                                <input name="image_url" type="url" value="{{ filter_var($offer->image_url, FILTER_VALIDATE_URL) ? $offer->image_url : '' }}" placeholder="رابط صورة اختياري" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs">
                                <textarea name="description" rows="2" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs">{{ $offer->description }}</textarea>
                                <label class="flex items-center gap-2 text-xs"><input type="checkbox" name="is_active" value="1" {{ $offer->is_active ? 'checked' : '' }}> فعال</label>
                                <button class="w-full rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white">حفظ التعديل</button>
                            </form>
                        </details>
                        <form action="{{ route('admin.offers.delete', $offer->id) }}" method="POST" class="mt-3" onsubmit="return confirm('هل تريد حذف هذا البنر؟')">@csrf @method('DELETE')<button class="w-full rounded-xl bg-red-50 px-3 py-2 text-xs font-bold text-red-700">حذف البنر</button></form>
                    </div>
                @empty
                    <p class="rounded-2xl bg-slate-50 p-8 text-center text-sm text-slate-400 md:col-span-2">لا توجد بنرات بعد. أضف أول صورة من النموذج أعلاه.</p>
                @endforelse
            </div>
        </section>
        @endif

        @if($activeSection === 'account')
        <section id="account" class="scroll-mt-6 bg-white border border-slate-200 p-6 rounded-3xl shadow-sm">
            <h2 class="text-xl font-bold text-slate-900">معلومات الحساب</h2>
            <p class="mt-2 text-sm text-slate-500">حساب مدير النظام المسؤول عن إدارة المنيو والطلبات.</p>
            <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2"><div class="rounded-2xl bg-slate-50 p-5"><p class="text-xs text-slate-500">نوع الحساب</p><p class="mt-2 font-bold text-slate-900">مدير النظام</p></div><div class="rounded-2xl bg-slate-50 p-5"><p class="text-xs text-slate-500">حالة النظام</p><p class="mt-2 font-bold text-emerald-600">متصل وجاهز للطلبات</p></div></div>
        </section>
        @endif

        @if(in_array($activeSection, ['dashboard', 'settings'], true))
        <section id="settings" class="scroll-mt-6 bg-white border border-slate-200 p-6 rounded-3xl shadow-sm">
            <div class="mb-5">
                <h2 class="text-xl font-bold text-slate-900">إعدادات المطعم</h2>
                <p class="mt-1 text-xs text-slate-500">البيانات التالية تُرسل إلى تطبيق Flutter ويمكن تعديلها يومياً.</p>
            </div>
            <form action="{{ route('admin.settings.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                @method('PUT')
                <input name="restaurant_name" value="{{ old('restaurant_name', $settings->restaurant_name ?? 'مطعم نجوم اليمن') }}" placeholder="اسم المطعم" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <input name="phone" value="{{ old('phone', $settings->phone ?? '') }}" placeholder="رقم الهاتف" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <input name="whatsapp_url" value="{{ old('whatsapp_url', $settings->whatsapp_url ?? '') }}" placeholder="رابط واتساب" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <input name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url ?? '') }}" placeholder="رابط إنستغرام" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <input name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url ?? '') }}" placeholder="رابط فيسبوك" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <input name="working_hours" value="{{ old('working_hours', $settings->working_hours ?? '') }}" placeholder="ساعات العمل" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <textarea name="address" rows="2" placeholder="العنوان" class="md:col-span-2 rounded-xl border border-slate-300 px-3 py-2.5 text-sm">{{ old('address', $settings->address ?? '') }}</textarea>
                <div class="md:col-span-2 flex justify-end"><button class="rounded-xl bg-red-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-red-700">حفظ الإعدادات</button></div>
            </form>
        </section>
        @endif
        @if(in_array($activeSection, ['dashboard', 'settings'], true))
        <section id="social" class="scroll-mt-6 rounded-3xl border border-red-100 bg-red-50 p-5 text-sm text-red-900">
            <p class="font-bold">التواصل الاجتماعي</p>
            <p class="mt-1 text-xs text-red-800/80">بعد حفظ الروابط ستظهر في أسفل Home والـDrawer داخل التطبيق.</p>
        </section>
        @endif
    </main>

    <!-- جافاسكريبت لفتح وإغلاق نموذج الإضافة -->
    <script>
        function toggleAddProductForm() {
            const form = document.getElementById('addProductFormContainer');
            if (form) {
                form.classList.toggle('hidden');
                form.scrollIntoView({ behavior: 'smooth' });
            }
        }
    </script>
</body>
</html>
