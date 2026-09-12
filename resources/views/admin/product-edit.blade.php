<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل وجبة - نجوم اليمن</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen p-6">
    <main class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold">تعديل الوجبة</h1>
                <p class="text-sm text-slate-500">{{ $product->name }}</p>
            </div>
            <a href="{{ route('admin.dashboard', ['category_id' => $product->category_id]) }}" class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-sm font-bold">العودة للمنيو</a>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">القسم</label>
                <select name="category_id" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">اسم الوجبة</label>
                <input name="name" value="{{ old('name', $product->name) }}" required class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">السعر (ريال)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">السعر السابق (اختياري)</label>
                <input type="number" step="0.01" name="old_price" value="{{ old('old_price', $product->old_price) }}" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-600 mb-1">الوصف</label>
                <textarea name="description" rows="3" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm">{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-600 mb-1">الصورة الحالية</label>
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-40 h-32 object-cover rounded-2xl border border-slate-200 mb-3">
                @endif
                <label class="block text-xs font-bold text-slate-600 mb-1">استبدال الصورة من الجهاز</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm">
                <p class="text-[11px] text-slate-400 mt-1">JPG أو PNG أو WebP — الحد الأقصى 5MB</p>
                <label class="block text-xs font-bold text-slate-600 mt-3 mb-1">أو رابط الصورة (اختياري)</label>
                <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm">
            </div>
            <div class="md:col-span-2 flex flex-wrap gap-5 bg-slate-50 border border-slate-200 rounded-xl p-4">
                <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }}> متوفر للطلب</label>
                <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}> صنف مميز</label>
                <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="is_daily_special" value="1" {{ $product->is_daily_special ? 'checked' : '' }}> متغير يومياً</label>
            </div>
            <div class="md:col-span-2 flex gap-3 pt-2">
                <button class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl">حفظ التعديلات</button>
                <a href="{{ route('admin.dashboard', ['category_id' => $product->category_id]) }}" class="bg-slate-200 text-slate-700 font-bold px-5 py-3 rounded-xl">إلغاء</a>
            </div>
        </form>
    </main>
</body>
</html>
