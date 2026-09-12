<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دخول إدارة مطعم نجوم اليمن</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: Cairo, sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-100 px-4 py-10">
    <main class="mx-auto flex min-h-[80vh] max-w-md items-center justify-center">
        <section class="w-full rounded-3xl bg-white p-6 shadow-xl sm:p-8">
            <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border-4 border-red-100 bg-white">
                <img src="{{ asset('storage/images/logo.png') }}"
                    alt="شعار مطعم نجوم اليمن"
                    class="h-full w-full object-contain">
            </div>
            <h1 class="text-center text-xl font-bold text-slate-900">دخول لوحة الإدارة</h1>
            <p class="mt-2 text-center text-sm text-slate-500">مطعم نجوم اليمن</p>

            @if($errors->any())
                <div class="mt-5 rounded-2xl bg-red-50 p-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <label class="block text-sm font-semibold text-slate-700">
                    البريد الإلكتروني
                    <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 outline-none focus:border-red-600 focus:ring-2 focus:ring-red-100">
                </label>
                <label class="block text-sm font-semibold text-slate-700">
                    كلمة المرور
                    <input name="password" type="password" required autocomplete="current-password" class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3 outline-none focus:border-red-600 focus:ring-2 focus:ring-red-100">
                </label>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input name="remember" type="checkbox" value="1" class="h-4 w-4 accent-red-600">
                    تذكرني على هذا الجهاز
                </label>
                <button type="submit" class="w-full rounded-2xl bg-red-700 px-4 py-3 font-bold text-white transition hover:bg-red-800">دخول لوحة الإدارة</button>
            </form>
        </section>
    </main>
</body>
</html>
