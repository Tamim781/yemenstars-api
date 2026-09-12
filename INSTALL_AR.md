# تعليمات تطبيق Patch Laravel

هذه الحزمة تصلح نسخة Laravel الحالية دون حذف قاعدة البيانات أو الصور.

## الملفات التي تم تعديلها

- `routes/web.php`
- `app/Http/Controllers/Admin/DashboardController.php`
- `app/Http/Controllers/Admin/CategoryController.php`
- `resources/views/admin/dashboard.blade.php`
- `app/Models/Category.php`
- `app/Models/Product.php`
- `app/Models/OrderItem.php`
- `app/Models/Address.php`
- `app/Models/Favorite.php`

## ما تم إصلاحه

- إضافة CRUD كامل للأقسام: إضافة، عرض، تعديل، رفع صورة، حذف.
- إضافة حذف وتعديل المنتجات مع رفع الصورة وتغيير حالة التوفر.
- منع حذف القسم إذا كان يحتوي على منتجات مرتبطة به حتى لا تنكسر العلاقات.
- إضافة علاقات `Product` مع `OrderItem` و`Favorite`.
- إضافة علاقة `OrderItem` مع `Product`.
- إضافة علاقة `Address` مع `User` وعلاقة `Favorite` مع `User`.
- توحيد أسماء ملفات `Category.php` و`Product.php` لتعمل على استضافة Linux.
- إبقاء Drawer والداشبورد الرئيسية الحالية، مع عرض محتوى الإدارة حسب القسم المختار.

## طريقة النسخ

1. خذ نسخة احتياطية من مشروع Laravel.
2. انسخ الملفات من الحزمة فوق المسارات نفسها داخل المشروع.
3. لا تنسخ مجلد `vendor` ولا ملف `.env` الموجود في الحزمة.
4. تأكد من وجود الرابط الرمزي للصور.

```powershell
php artisan storage:link
php artisan optimize:clear
php artisan route:list
php artisan migrate:status
```

لا تنفذ `migrate:fresh` لأنه يحذف البيانات الحالية.

## اختبار لوحة الإدارة

شغّل:

```powershell
php artisan serve --host=0.0.0.0 --port=8001
```

ثم افتح:

```text
http://127.0.0.1:8001/admin?section=categories
```

اختبر إضافة قسم بصورة، ثم تعديل اسمه أو صورته، ثم حاول حذفه. إذا كان للقسم منتجات، سيمنع النظام الحذف ويحافظ على سلامة العلاقات.

بعد ذلك افتح:

```text
http://127.0.0.1:8001/admin?section=products
```

واختبر إضافة منتج، تعديل المنتج، حذف المنتج، وتغيير حالة توفره.

## ملاحظة مهمة

بيئة المراجعة لا تحتوي PHP، لذلك يجب تنفيذ `php artisan` على جهاز Windows لديك. إذا ظهر خطأ، أرسل ناتج الأمر كاملاً قبل تغيير قاعدة البيانات.
