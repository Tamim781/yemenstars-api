<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Offer;
use App\Models\PaymentMethod;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@yemenstars.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('admin123456'),
                'phone' => '775806564',
                'role' => 'admin',
                'is_admin' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@yemenstars.com'],
            [
                'name' => 'تميم الحمودي',
                'password' => Hash::make('12345678'),
                'phone' => '770000000',
                'role' => 'customer',
                'is_admin' => false,
            ]
        );

        Setting::updateOrCreate(['id' => 1], [
            'restaurant_name' => 'مطعم نجوم اليمن',
            'logo_url' => '/images/yemen-stars-logo.jpg',
            'phone' => '+967 777777777',
            'address' => 'صنعاء، اليمن',
            'working_hours' => '10:00 صباحاً - 12:00 منتصف الليل',
            'delivery_fee' => 500.00,
            'jaib_account' => '777777777',
            'jawali_account' => '777777777',
            'kuraimi_account' => '123456789',
        ]);

        // نفس الأقسام وترتيبها الظاهرين في لوحة الإدارة الأصلية.
        $categoryNames = [
            'البروست',
            'المشاوي',
            'العريكة',
            'أطباق متنوعة',
            'الصانونة',
            'السحاوق',
            'المشروبات',
            'المخبازة',
        ];

        $categoryIds = [];
        foreach ($categoryNames as $index => $name) {
            $category = Category::updateOrCreate(
                ['name' => $name],
                ['sort_order' => $index + 1]
            );
            $categoryIds[$name] = $category->id;
        }

        // بيانات المنيو الحقيقية المستخرجة من لوحة الإدارة الأصلية: 65 صنفاً.
        $menu = [
            // البروست (12)
            ['category' => 'البروست', 'name' => 'ديرك بروست', 'price' => 6000, 'description' => 'وجبة بروست ديرك طازجة'],
            ['category' => 'البروست', 'name' => 'أصابع بروست صافي', 'price' => 5000, 'description' => 'أصابع بروست سمك صافي مقرمشة'],
            ['category' => 'البروست', 'name' => 'جمبري بروست جامبو', 'price' => 6000, 'description' => 'جمبري جامبو مقلي بالخلطة الخاصة'],
            ['category' => 'البروست', 'name' => 'جمبري بروست وسط', 'price' => 5000, 'description' => 'جمبري وسط مقرمش وطازج'],
            ['category' => 'البروست', 'name' => 'أصابع ولد بروست', 'price' => 3500, 'description' => 'أصابع بروست خاصة للأطفال'],
            ['category' => 'البروست', 'name' => 'قد أصفر بروست', 'price' => 5500, 'description' => 'قد أصفر مقلي بروست'],
            ['category' => 'البروست', 'name' => 'قد أسود بروست', 'price' => 4000, 'description' => 'قد أسود بروست مقرمش'],
            ['category' => 'البروست', 'name' => 'بياض وصال بروست', 'price' => 5000, 'description' => 'بياض وصال بروست طازج'],
            ['category' => 'البروست', 'name' => 'شروخ بروست', 'price' => 8000, 'description' => 'شروخ بحرية بروست فاخرة'],
            ['category' => 'البروست', 'name' => 'باغة بروست', 'price' => 2000, 'description' => 'باغة بروست شهية'],
            ['category' => 'البروست', 'name' => 'ثمد بروست', 'price' => 5000, 'description' => 'ثمد بروست مقرمش ولذيذ'],
            ['category' => 'البروست', 'name' => 'أصابع سخلة بروست', 'price' => 7500, 'description' => 'أصابع لحم سخلة بروست'],

            // المشاوي (13)
            ['category' => 'المشاوي', 'name' => 'جحش', 'price' => 5000, 'description' => 'سمك مشي طازج على الفحم'],
            ['category' => 'المشاوي', 'name' => 'بياض', 'price' => 4000, 'description' => 'سمك بياض مشوي بالتوابل'],
            ['category' => 'المشاوي', 'name' => 'قد أصفر', 'price' => 5000, 'description' => 'سمك قد أصفر مشوي'],
            ['category' => 'المشاوي', 'name' => 'قد أسود', 'price' => 4000, 'description' => 'سمك قد أسود مشوي'],
            ['category' => 'المشاوي', 'name' => 'ناقم', 'price' => 3500, 'description' => 'سمك ناقم مشوي عالفحم'],
            ['category' => 'المشاوي', 'name' => 'بكاس', 'price' => 4500, 'description' => 'سمك بكاس مشوي'],
            ['category' => 'المشاوي', 'name' => 'عنتق', 'price' => 3500, 'description' => 'سمك عنتق مشوي'],
            ['category' => 'المشاوي', 'name' => 'ديرك', 'price' => 6000, 'description' => 'شريحة سمك ديرك مشوية'],
            ['category' => 'المشاوي', 'name' => 'سخلة صافي', 'price' => 7500, 'description' => 'سخلة صافي مشوية فاخرة'],
            ['category' => 'المشاوي', 'name' => 'أبو مقص', 'price' => 2500, 'description' => 'سمك أبو مقص مشوي'],
            ['category' => 'المشاوي', 'name' => 'عربي', 'price' => 5000, 'description' => 'سمك عربي مشوي'],
            ['category' => 'المشاوي', 'name' => 'مرجان', 'price' => 7000, 'description' => 'سمك مرجان مشوي بالخلطة اليمنية'],
            ['category' => 'المشاوي', 'name' => 'هامور', 'price' => 6000, 'description' => 'فيليه هامور مشوي على الفحم'],

            // العريكة (7)
            ['category' => 'العريكة', 'name' => 'عريكة ملكي دبل قشطة', 'price' => 2000, 'description' => 'عريكة يمنية أصلية بالقشطة والعسل والمكسرات الفاخرة'],
            ['category' => 'العريكة', 'name' => 'عريكة مكسرات', 'price' => 1500, 'description' => 'عريكة تقليدية مع المشاوي والمكسرات والسمن البلدي'],
            ['category' => 'العريكة', 'name' => 'فتة تمر', 'price' => 1000, 'description' => 'فتة تمر بالسمن والعسل'],
            ['category' => 'العريكة', 'name' => 'فتة موز', 'price' => 800, 'description' => 'فتة موز بالخبز البر والعسل'],
            ['category' => 'العريكة', 'name' => 'فتة عسل', 'price' => 800, 'description' => 'فتة بالسمن والعسل الطبيعي'],
            ['category' => 'العريكة', 'name' => 'فتة بر ناشف', 'price' => 700, 'description' => 'فتة بر ناشف تقليدية'],
            ['category' => 'العريكة', 'name' => 'معصوب قشطة وعسل', 'price' => 1500, 'description' => 'معصوب موز بالقشطة والعسل'],

            // أطباق متنوعة (13)
            ['category' => 'أطباق متنوعة', 'name' => 'أصابع بروست', 'price' => 1500, 'description' => 'طبق أصابع بروست (النفر)'],
            ['category' => 'أطباق متنوعة', 'name' => 'جمبري جامبو', 'price' => 2000, 'description' => 'جمبري جامبو (النفر)'],
            ['category' => 'أطباق متنوعة', 'name' => 'جمبري وسط', 'price' => 1500, 'description' => 'جمبري وسط (النفر)'],
            ['category' => 'أطباق متنوعة', 'name' => 'ديرك بروست', 'price' => 2000, 'description' => 'ديرك بروست (النفر)'],
            ['category' => 'أطباق متنوعة', 'name' => 'ثمد بروست', 'price' => 1500, 'description' => 'ثمد بروست (النفر)'],
            ['category' => 'أطباق متنوعة', 'name' => 'سخلة بروست', 'price' => 2250, 'description' => 'سخلة بروست (النفر)'],
            ['category' => 'أطباق متنوعة', 'name' => 'شروخ بروست', 'price' => 2500, 'description' => 'شروخ بروست (النفر)'],
            ['category' => 'أطباق متنوعة', 'name' => 'حبة سمك مشوي', 'price' => 2500, 'description' => 'حبة سمك مشوي (النفر)'],
            ['category' => 'أطباق متنوعة', 'name' => 'صانونة جمبري جامبو', 'price' => 2000, 'description' => 'صانونة جمبري جامبو (النفر)'],
            ['category' => 'أطباق متنوعة', 'name' => 'صانونة جمبري وسط', 'price' => 1500, 'description' => 'صانونة جمبري وسط (النفر)'],
            ['category' => 'أطباق متنوعة', 'name' => 'سمك ربيس', 'price' => 1500, 'description' => 'سمك ربيس (النفر)'],
            ['category' => 'أطباق متنوعة', 'name' => 'صانونة سمك', 'price' => 1500, 'description' => 'صانونة سمك (النفر)'],
            ['category' => 'أطباق متنوعة', 'name' => 'رز شعبي', 'price' => 500, 'description' => 'رز شعبي مبهر (النفر)'],

            // الصانونة (6)
            ['category' => 'الصانونة', 'name' => 'جمبري جامبو (كيلو)', 'price' => 6000, 'description' => 'صانونة جمبري جامبو طازجة بالمرق اليمني'],
            ['category' => 'الصانونة', 'name' => 'جمبري وسط (كيلو)', 'price' => 5000, 'description' => 'صانونة جمبري وسط'],
            ['category' => 'الصانونة', 'name' => 'سمك ربيس (كيلو)', 'price' => 5000, 'description' => 'صانونة سمك ربيس طازج'],
            ['category' => 'الصانونة', 'name' => 'سمك صانونة (كيلو)', 'price' => 4500, 'description' => 'صانونة سمك مشكلة'],
            ['category' => 'الصانونة', 'name' => 'شروخ صانونة (كيلو)', 'price' => 7500, 'description' => 'شروخ بحرية صانونة فاخرة'],
            ['category' => 'الصانونة', 'name' => 'سخلة صانونة (كيلو)', 'price' => 7500, 'description' => 'سخلة صانونة مرق خاص'],

            // السحاوق (4)
            ['category' => 'السحاوق', 'name' => 'سحاوق هرشة', 'price' => 1000, 'description' => 'سحاوق يمني حار بالثوم والطماطم'],
            ['category' => 'السحاوق', 'name' => 'سحاوق جبن', 'price' => 500, 'description' => 'سحاوق تقليدي بالجبن البلدي'],
            ['category' => 'السحاوق', 'name' => 'نص سحاوق جبن', 'price' => 300, 'description' => 'نص وجبة سحاوق جبن'],
            ['category' => 'السحاوق', 'name' => 'سحاوق أحمر', 'price' => 300, 'description' => 'سحاوق أحمر حار'],

            // المشروبات (4)
            ['category' => 'المشروبات', 'name' => 'جاك ليمون', 'price' => 1000, 'description' => 'جاك عصير ليمون طازج ومنعش'],
            ['category' => 'المشروبات', 'name' => 'صحة ليمون', 'price' => 500, 'description' => 'عصير ليمون بارد'],
            ['category' => 'المشروبات', 'name' => 'ماء', 'price' => 100, 'description' => 'ماء معدني نقي'],
            ['category' => 'المشروبات', 'name' => 'قوة أسد', 'price' => 150, 'description' => 'مشروب طاقة منعش'],

            // المخبازة (6)
            ['category' => 'المخبازة', 'name' => 'ملوح مثلث', 'price' => 1000, 'description' => 'خبز ملوح يمني مثلث ساخن'],
            ['category' => 'المخبازة', 'name' => 'ملوح دبل', 'price' => 500, 'description' => 'ملوح دبل بالسمن البلدي'],
            ['category' => 'المخبازة', 'name' => 'ملوح سنجل', 'price' => 300, 'description' => 'ملوح سنجل طازج'],
            ['category' => 'المخبازة', 'name' => 'رطب مثلث', 'price' => 1000, 'description' => 'خبز رطب مثلث تنور'],
            ['category' => 'المخبازة', 'name' => 'رطب دبل', 'price' => 500, 'description' => 'رطب دبل ساخن'],
            ['category' => 'المخبازة', 'name' => 'كرس', 'price' => 1000, 'description' => 'خبز كرس تنور تقليدي'],
        ];

        foreach ($menu as $item) {
            $featured = in_array($item['category'], ['البروست', 'المشاوي', 'العريكة'], true);
            Product::updateOrCreate(
                [
                    'category_id' => $categoryIds[$item['category']],
                    'name' => $item['name'],
                ],
                [
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'is_available' => true,
                    'is_featured' => $featured,
                    'is_daily_special' => false,
                ]
            );
        }

        PaymentMethod::updateOrCreate(
            ['code' => 'cash_on_delivery'],
            [
                'name' => 'الدفع عند الاستلام',
                'instructions' => 'ادفع للمندوب عند استلام الطلب.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
        PaymentMethod::updateOrCreate(
            ['code' => 'jaib'],
            [
                'name' => 'جيب',
                'instructions' => 'حوّل المبلغ إلى حساب المطعم ثم أرسل رقم العملية.',
                'account_number' => '777777777',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );
        PaymentMethod::updateOrCreate(
            ['code' => 'jawali'],
            [
                'name' => 'جوالي',
                'instructions' => 'حوّل المبلغ إلى حساب المطعم ثم أرسل رقم العملية.',
                'account_number' => '777777777',
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        Offer::updateOrCreate(
            ['title' => 'أصالة المذاق اليمني'],
            [
                'description' => 'اكتشف أصنافنا اليومية من البروست والمشاوي والمخبازة.',
                'action_type' => 'none',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }
}
