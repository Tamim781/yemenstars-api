#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
=============================================================================
مستشار وشيف مطعم نجوم اليمن للأسماك - محرك الذكاء الاصطناعي اليمني الأصيل
Yemen Stars Fish Restaurant - Authentic AI Chef & Consultation Model
=============================================================================
المميزات الأساسية:
1. استيعاب كامل للهجة الصنعانية واليمنية الأصيلة وأعراف الضيافة البحرية.
2. التزام صارم بحدود المطعم (بدون سياسة، بدون خوض في مواضيع خارج المطعم، وتوجيه تتبع الطلبات للريسبشن).
3. **ضمان عدم تخليق أي صنف وهمي (Zero Hallucinations Guarantee)**:
   كل صنف مقترح ومضاف للسلة هو صنف حقيقي 100% موجود في قاعدة بيانات المطعم
   مع معرفه (ID)، وسعره الدقيق، وقسمه.
4. حساب هندسي ديناميكي لكميات الوجبات بناء على عدد الأشخاص، الميزانية، أو المناسبة.
=============================================================================
"""

import os
import sys
import json
import re
from typing import Dict, List, Any, Optional, Tuple

if sys.stdout.encoding != 'utf-8':
    try:
        sys.stdout.reconfigure(encoding='utf-8')
    except Exception:
        pass

class YemenStarsChefAI:
    def __init__(self, catalog_path: Optional[str] = None):
        if catalog_path is None:
            base_dir = os.path.dirname(os.path.abspath(__file__))
            catalog_path = os.path.join(base_dir, "restaurant_catalog.json")

        self.catalog_path = catalog_path
        self.restaurant_info = {}
        self.products: List[Dict[str, Any]] = []
        self.fish_species: List[Dict[str, Any]] = []
        self._load_catalog()

    def _load_catalog(self):
        """تحميل كتالوج الأصناف المعتمدة من المطعم"""
        if os.path.exists(self.catalog_path):
            with open(self.catalog_path, "r", encoding="utf-8") as f:
                data = json.load(f)
                self.restaurant_info = data.get("restaurant_info", {})
                self.products = data.get("products", [])
                self.fish_species = data.get("fish_species", [])
        else:
            self._init_fallback_products()

    def _init_fallback_products(self):
        """بيانات احتياطية مطابقة تماماً لقاعدة بيانات التطبيق الحقيقية"""
        self.restaurant_info = {
            "name": "مطعم نجوم اليمن للأسماك",
            "city": "صنعاء",
            "location": "باب القاع - سوق السمك - خلف مستشفى الجمهوري مباشرة",
            "phones": ["771000272", "738860666"],
            "working_hours": "يومياً من 9:00 صباحاً حتى 11:30 ليلاً",
            "delivery_available": True,
            "whatsapp": "967771000272"
        }
        self.products = [
            {"id": 1, "category_id": 1, "name": "ديرك بروست", "price": 6000.0, "unit": "وجبة", "description": "بروست ديرك طازج ومقرمش"},
            {"id": 2, "category_id": 1, "name": "جمبري بروست جامبو", "price": 6000.0, "unit": "وجبة", "description": "جمبري جامبو مقلي مقرمش"},
            {"id": 3, "category_id": 1, "name": "أصابع بروست صافي", "price": 5000.0, "unit": "وجبة", "description": "أصابع بروست سمك صافي"},
            {"id": 4, "category_id": 2, "name": "سمك ديرك مشوي", "price": 6000.0, "unit": "كيلو", "description": "سمك ديرك مشوي على الفحم"},
            {"id": 5, "category_id": 2, "name": "سخلة صافي مشوية", "price": 7500.0, "unit": "كيلو", "description": "سخلة صافي مشوية فاخرة"},
            {"id": 6, "category_id": 2, "name": "سمك جحش مشوي", "price": 5000.0, "unit": "كيلو", "description": "سمك جحش مشوي فحم"},
            {"id": 7, "category_id": 2, "name": "سمك بياض مشوي", "price": 4000.0, "unit": "كيلو", "description": "سمك بياض مشوي بالتوابل والليمون"},
            {"id": 8, "category_id": 3, "name": "عريكة ملكي دبل قشطة", "price": 2000.0, "unit": "صحن", "description": "عريكة يمنية بالقشطة والعسل والمكسرات"},
            {"id": 9, "category_id": 3, "name": "عريكة مكسرات", "price": 1500.0, "unit": "صحن", "description": "عريكة تقليدية مع السمن والمكسرات"},
            {"id": 10, "category_id": 5, "name": "صانونة جمبري جامبو", "price": 6000.0, "unit": "مدرة", "description": "صانونة جمبري في مدرة حجرية"},
            {"id": 11, "category_id": 5, "name": "صانونة سمك ربيس", "price": 5000.0, "unit": "مدرة", "description": "صانونة سمك ربيس بالمرق الساخن"},
            {"id": 12, "category_id": 6, "name": "سحاوق هرشة", "price": 1000.0, "unit": "صحن", "description": "سحاوق يمني حار بالثوم والطماطم"},
            {"id": 13, "category_id": 6, "name": "سحاوق جبن بلدي", "price": 500.0, "unit": "صحن", "description": "سحاوق بالجبن البلدي"},
            {"id": 14, "category_id": 7, "name": "جاك عصير ليمون", "price": 1000.0, "unit": "جاك", "description": "عصير ليمون بالنعناع منعش"},
            {"id": 15, "category_id": 8, "name": "ملوح مثلث تنور", "price": 1000.0, "unit": "حبة دبل", "description": "خبز ملوح يمني مثلث ساخن ومقرمش"},
            {"id": 16, "category_id": 8, "name": "خبز رطب تنور", "price": 500.0, "unit": "قرص", "description": "خبز رطب تنور تقليدي ساخن"}
        ]

    def update_catalog(self, new_products: List[Dict[str, Any]]):
        """تحديث قائمة المنتجات لحظياً من قبل الأدمن أو تطبيق فلاتر"""
        if new_products:
            self.products = new_products
            try:
                with open(self.catalog_path, "w", encoding="utf-8") as f:
                    json.dump({
                        "restaurant_info": self.restaurant_info,
                        "products": self.products,
                        "fish_species": self.fish_species
                    }, f, ensure_ascii=False, indent=2)
            except Exception as e:
                print(f"Error saving updated catalog: {e}")

    def find_product_by_id(self, product_id: int) -> Optional[Dict[str, Any]]:
        for p in self.products:
            if p.get("id") == product_id:
                return p
        return None

    def find_product_by_keyword(self, keyword: str) -> Optional[Dict[str, Any]]:
        kw = keyword.strip().lower()
        for p in self.products:
            name = p.get("name", "").lower()
            if kw in name or name in kw:
                return p
        return None

    def extract_person_count(self, message: str) -> Optional[int]:
        """استخراج عدد الأشخاص من الرسالة بدقة وفهم الكلمات اليمنية"""
        msg = message.lower()
        # فحص مباشر بالأرقام
        num_match = re.search(r'(\d+)\s*(أشخاص|اشخاص|أنفار|انفار|نفر|شخص|حبات|رجال)', msg)
        if num_match:
            return int(num_match.group(1))

        # فحص الكلمات التعبيرية
        if any(w in msg for w in ["شخص واحد", "نفر واحد", "لحالي", "فردي", "واحد بس", "لي لوحدي"]):
            return 1
        if any(w in msg for w in ["شخصين", "نفرين", "اثنين", "إثنين", "انا وصاحبي", "انا وزوجتي", "2-3", "2 الى 3", "2 إلى 3"]):
            return 2
        if any(w in msg for w in ["ثلاثة", "ثلاثه", "3 انفار", "3 أشخاص"]):
            return 3
        if any(w in msg for w in ["أربعة", "اربعه", "4 اشخاص", "4 انفار"]):
            return 4
        if any(w in msg for w in ["خمسة", "خمسه", "5 اشخاص", "5 انفار"]):
            return 5
        if any(w in msg for w in ["ستة", "سته", "6 اشخاص", "6 انفار"]):
            return 6
        if any(w in msg for w in ["سبعة", "سبعه", "7 اشخاص", "7 انفار"]):
            return 7
        if any(w in msg for w in ["ثمانية", "ثمانيه", "8 اشخاص", "8 انفار"]):
            return 8
        if any(w in msg for w in ["تسعة", "تسعه", "9 اشخاص"]):
            return 9
        if any(w in msg for w in ["عشرة", "عشره", "10 اشخاص", "عزومة كبيرة", "عزيمة كبيرة", "عائلة كبيرة", "جمعة كبيرة", "سفرة عائلية كبيرة"]):
            return 8
        if any(w in msg for w in ["عزومة", "عزيمة", "عائلة", "عائله"]):
            return 5

        # فحص مجرد أرقام
        single_digit = re.search(r'\b([1-9]|10)\b', msg)
        if single_digit:
            return int(single_digit.group(1))

        return None

    def compose_verified_meal(self, persons: int) -> Tuple[List[Dict[str, Any]], float]:
        """
        تركيب وجبة مضمونة 100% مكونة حصرياً من الأصناف الحقيقية الموجودة في قاعدة البيانات!
        لا يوجد أي تخليق أو صنف وهمي على الإطلاق.
        """
        bundle: List[Dict[str, Any]] = []

        # استخراج الأصناف الأساسية الحقيقية من الكتالوج
        sakhla = self.find_product_by_keyword("سخلة صافي") or self.find_product_by_keyword("سمك ديرك مشوي") or self.products[0]
        dayrak_mashwi = self.find_product_by_keyword("سمك ديرك مشوي") or sakhla
        shrimp_broast = self.find_product_by_keyword("جمبري بروست جامبو") or self.find_product_by_keyword("ديرك بروست")
        sanona_shrimp = self.find_product_by_keyword("صانونة جمبري جامبو") or self.find_product_by_keyword("صانونة سمك ربيس")
        melwah = self.find_product_by_keyword("ملوح مثلث تنور") or self.find_product_by_keyword("خبز رطب")
        sahawaoq_har = self.find_product_by_keyword("سحاوق هرشة")
        sahawaoq_jibin = self.find_product_by_keyword("سحاوق جبن بلدي")
        lemon_juice = self.find_product_by_keyword("جاك عصير ليمون")
        arika_royal = self.find_product_by_keyword("عريكة ملكي دبل قشطة") or self.find_product_by_keyword("عريكة مكسرات")

        def add_item(prod: Optional[Dict[str, Any]], qty: int):
            if prod is None:
                return
            bundle.append({
                "id": prod["id"],
                "name": prod["name"],
                "price": float(prod["price"]),
                "quantity": qty,
                "unit": prod.get("unit", "وجبة"),
                "total": float(prod["price"]) * qty,
                "category_id": prod.get("category_id", 1),
                "product": prod
            })

        if persons == 1:
            # شخص واحد: سمك مشوي + ملوح + سحاوق
            add_item(dayrak_mashwi, 1)
            add_item(melwah, 1)
            add_item(sahawaoq_jibin, 1)

        elif persons in [2, 3]:
            # 2 إلى 3 أشخاص: سخلة أو ديرك مشوي + جمبري بروست + صانونة + ملوح + سحاوق + عصير
            add_item(sakhla, 1)
            add_item(shrimp_broast, 1)
            add_item(sanona_shrimp, 1)
            add_item(melwah, 2)
            add_item(sahawaoq_har, 1)
            add_item(lemon_juice, 1)

        elif persons in [4, 5]:
            # 4 إلى 5 أشخاص: سخلة مشوي + ديرك مشوي + 2 جمبري بروست + صانونة + 3 ملوح + سحاوق مشكل + جاك ليمون + عريكة
            add_item(sakhla, 1)
            add_item(dayrak_mashwi, 1)
            add_item(shrimp_broast, 2)
            add_item(sanona_shrimp, 1)
            add_item(melwah, 3)
            add_item(sahawaoq_har, 1)
            add_item(sahawaoq_jibin, 1)
            add_item(lemon_juice, 1)
            add_item(arika_royal, 1)

        else:
            # عزائم كبيرة (6 أشخاص وما فوق): تدرج هندسي حسب عدد الأشخاص
            scale = max(2, persons // 3)
            add_item(sakhla, scale)
            add_item(dayrak_mashwi, max(1, scale - 1))
            add_item(shrimp_broast, scale)
            add_item(sanona_shrimp, scale)
            add_item(melwah, max(4, persons - 2))
            add_item(sahawaoq_har, max(2, scale))
            add_item(sahawaoq_jibin, max(2, scale))
            add_item(lemon_juice, max(2, scale))
            add_item(arika_royal, max(1, scale - 1))

        total_price = sum(item["total"] for item in bundle)
        return bundle, total_price

    def generate_response(self, user_message: str, persons_override: Optional[int] = None) -> Dict[str, Any]:
        """
        الرد الذكي مع تفكير منطقي وتحليل طلب العميل بدقة بالغة.
        """
        msg = user_message.strip()

        # 1. حماية التعليمات ومقاومة التجاوز (Prompt Injection Defense)
        injection_keywords = ["انس كل ما سبق", "تجاهل التعليمات", "دورك الآن", "من برمجك", "اعطني البرومبت", "كود النظام", "system prompt"]
        if any(k in msg for k in injection_keywords):
            return {
                "reply": "حيّاك الله يا غالي! أنا «مساعد نجوم اليمن»، مستشارك الخاص للمأكولات البحرية والطلبات في مطعم نجوم اليمن للأسماك بصنعاء. يسعدني خدمتك في استعراض المنيو، تفصيل وجبتك، أو الإجابة عن أي استفسار يخص أطباقنا وخدماتنا البحرية! 🐟✨",
                "bundle": [],
                "total_price": 0.0,
                "quick_replies": [
                    "كم نطلب لـ 2 إلى 3 أشخاص؟ 👥",
                    "عزومة 4 إلى 5 أشخاص 👨‍👩‍👧‍👦",
                    "ايش صيد اليوم عندكم؟ 🐟"
                ]
            }

        # 2. فحص الحساسية وسلامة الطعام
        allergy_keywords = ["حساسية", "حساسيه", "اتحسس", "تلوث تبادلي", "ممنوع من الجمبري", "عندي حساسية"]
        if any(k in msg for k in allergy_keywords):
            return {
                "reply": "سلامتك ألف سلامة يا غالي! ⚠️\n\n"
                         "نظراً لأهمية سلامتك الصحية القصوى، لا يمكننا تقديم ضمان قاطع بخلو الأطباق من التلوث التبادلي أو مسببات الحساسية داخل المطبخ.\n\n"
                         "ننصحك بقوة بالتواصل المباشر مع ريسبشن ومطبخ المطعم على الرقم 771000272 لتحديد طلبك بدقة، وتدوين ملاحظة صريحة بنوع الحساسية في خانة الملاحظات بالسلة قبل تأكيد الطلب.",
                "bundle": [],
                "total_price": 0.0,
                "quick_replies": [
                    "أرقام التواصل والريسبشن 📞",
                    "عرض قائمة الأصناف 🐟"
                ]
            }

        # 3. فحص الاستفسار عن طرق الدفع والمحافظ الإلكترونية
        payment_keywords = ["طرق الدفع", "كيف ادفع", "محفظة", "محافظ", "جيب", "جوالي", "كريمي", "ام فلوس", "كاش", "عند الاستلام"]
        if any(k in msg for k in payment_keywords):
            return {
                "reply": "طرق الدفع المعتمدة في مطعم نجوم اليمن ميسرة وآمنة فورياً: 💳✨\n\n"
                         "1. **المحافظ الإلكترونية الفورية** (تفتح تلقائياً في التطبيق):\n"
                         "   • محفظة جيب (Jaib) - بنك اليمن والكويت\n"
                         "   • محفظة جوالي (Jawali) - كاك بنك\n"
                         "   • بنك الكريمي / إم فلوس (M-Floos)\n\n"
                         "2. **الدفع نقداً عند الاستلام (كاش)**:\n"
                         "   • متاح للطلبات حتى 5,000 ريال يمني.\n\n"
                         "💡 ملاحظة أمان: لا يطلب تطبيقنا منك أبداً أي كلمة مرور أو رمز OTP سري.",
                "bundle": [],
                "total_price": 0.0,
                "quick_replies": [
                    "كم نطلب لـ 2 إلى 3 أشخاص؟ 👥",
                    "طلب فردي لشخص واحد 👤",
                    "سلة المشتريات 🛒"
                ]
            }

        # 4. فحص الاستفسار عن مكان السائق وتتبع الطلب (حدود صارمة)
        tracking_keywords = ["وين وصل", "أين وصل", "حالة الطلب", "تتبع", "رقم طلبي", "طلبي تأخر", "السائق", "متى يوصل", "طلبي وين"]
        if any(k in msg for k in tracking_keywords):
            return {
                "reply": "حيّاك الله يا غالي ونورت نجوم اليمن! 🐟✨\n\n"
                         "بخصوص تتبع مسار الطلب ومكان السائق مباشرة، تفضل بفتح شاشة 'طلباتي ومتابعة التجهيز' في التطبيق لرؤية العداد ومسار السائق، أو التواصل مباشرة مع الكاشير والريسبشن عبر الرقم 771000272.\n\n"
                         "وأنا هنا رهن إشارتك لمساعدتك في تفصيل الوجبات البحرية واختيار أطزج صيد اليوم!",
                "bundle": [],
                "total_price": 0.0,
                "quick_replies": [
                    "كم نطلب لـ 2 إلى 3 أشخاص؟ 👥",
                    "عزومة 4 إلى 5 أشخاص 👨‍👩‍👧‍👦",
                    "ايش صيد اليوم عندكم؟ 🐟"
                ]
            }

        # 5. التثقيف البحري العام
        edu_keywords = ["تثقيف", "فوائد", "اوميغا", "قوام", "طعم السخلة", "طعم الديرك", "طعم الجحش", "طعم البياض", "افضل سمك", "انواع السمك"]
        if any(k in msg for k in edu_keywords) and not self.extract_person_count(msg):
            return {
                "reply": "معلومات بحرية موثوقة من بحار اليمن إلى مائدتكم: 🌊🐟\n\n"
                         "• 👑 **سمك السخلة**: أفخر الأسماك لحماً، ناصع البياض وطري جداً يذوب في الفم، ممتاز في الموفي والفرن.\n"
                         "• 🐟 **سمك الديرك**: جزل متماسكة وغنية بالنكهة البحرية والأوميغا 3 والبروتين الصافي، رائع على الفحم وبروست مقرمش.\n"
                         "• 🦐 **الجمبري الجامبو**: غني بالفسفور والمعادن، طعمه سكري خفيف ومحبب للكبار والصغار.\n"
                         "• 🍲 **سمك الربيس والبياض**: خفيف على المعدة ومثالي للصواني والإيدامات في المدرة الحجرية.\n\n"
                         "*(ملاحظة: هذه معلومات تثقيفية عامة؛ لمطابقة توفر الصيد الطازج اليوم يمكنك مراجعة المنيو أو سؤالي عن عدد الأشخاص)*",
                "bundle": [],
                "total_price": 0.0,
                "quick_replies": [
                    "كم نطلب لـ 2 إلى 3 أشخاص؟ 👥",
                    "عزومة 4 إلى 5 أشخاص 👨‍👩‍👧‍👦",
                    "ايش صيد اليوم عندكم؟ 🐟"
                ]
            }

        # 6. فحص المواضيع الخارجة عن المطعم (سياسة، دين، مواضيع عامة)
        off_topic = ["سياسة", "رئيس", "حزب", "انتخابات", "حرب", "دين", "مذهب", "كرة قدم", "برشلونة", "ريال مدريد", "فتوى", "علاج", "دواء"]
        if any(w in msg for w in off_topic):
            return {
                "reply": "حيّاك الله يا طيب.. أنا المساعد الخاص بتطبيق مطعم نجوم اليمن للأسماك، واختصاصي يتركز بالكامل في المأكولات البحرية الطازجة وخدمات المطعم! 🐟🌊\n\n"
                         "دعنا من هذه المواضيع، وإذا في خاطرك صيد بحري طازج أو حابب أفصل لك وجبة تناسب جمعتكم الكريمة، تفضل قولي كم عددكم وبضبط لك سفرة تشرفك!",
                "bundle": [],
                "total_price": 0.0,
                "quick_replies": [
                    "ايش صيد اليوم عندكم؟ 🐟",
                    "كم نطلب لـ 2 إلى 3 أشخاص؟ 👥",
                    "عزومة 4 إلى 5 أشخاص 👨‍👩‍👧‍👦"
                ]
            }

        # 7. التحيات والسلام
        greetings = ["مرحبا", "أهلا", "اهلا", "سلام", "السلام عليكم", "صباح الخير", "مساء الخير", "حي الله", "يا معلم", "يا مدير", "هلا", "حيّاك", "منور"]
        if msg in greetings or any(msg.startswith(g) for g in ["مرحبا", "سلام", "السلام عليكم", "هلا"]):
            if not self.extract_person_count(msg):
                return {
                    "reply": "يا هلا ومية مرحباً بنور العين! 🐟✨\n\n"
                             "نورت مطعم نجوم اليمن يا غالي.. آمر وتدلل، إيش مشتهي اليوم من بحرنا؟ قولي كم عددكم الكرام أو نوع السمك اللي في خاطرك، وبضبط لك أشهى وأطيب سفرة تشرفك وتكفيكم بالتمام بدون أي زيادة أو نقص!",
                    "bundle": [],
                    "total_price": 0.0,
                    "quick_replies": [
                        "كم نطلب لـ 2 إلى 3 أشخاص؟ 👥",
                        "عزومة 4 إلى 5 أشخاص 👨‍👩‍👧‍👦",
                        "عزيمة كبيرة (6 وما فوق) 👑",
                        "طلب فردي لشخص واحد 👤",
                        "ايش صيد اليوم عندكم؟ 🐟"
                    ]
                }

        # 4. الاستفسار عن الموقع وساعات العمل
        location_keywords = ["موقعكم", "مكانكم", "وين انتم", "سوق السمك", "متى تفتحوا", "الدوام", "العنوان", "الفرع"]
        if any(k in msg for k in location_keywords):
            return {
                "reply": "تشرفنا وتنورنا في أي وقت يا غالي! 📍\n\n"
                         f"• **الموقع**: {self.restaurant_info.get('location', 'صنعاء - باب القاع - سوق السمك')}\n"
                         f"• **أوقات العمل**: {self.restaurant_info.get('working_hours', 'يومياً من 9:00 صباحاً حتى 11:30 ليلاً')}\n"
                         f"• **أرقام التواصل والطلبات**: 771000272 - 738860666\n"
                         "• تتوفر لدينا صالة عائلية وشبابية مكيفة ومريحة، وخدمة توصيل فورية لجميع أحياء صنعاء!",
                "bundle": [],
                "total_price": 0.0,
                "quick_replies": [
                    "هل في خدمة توصيل؟ 🛵",
                    "كم نطلب لـ 2 إلى 3 أشخاص؟ 👥",
                    "ايش صيد اليوم عندكم؟ 🐟"
                ]
            }

        # 5. الاستفسار عن التوصيل
        if any(k in msg for k in ["توصيل", "توصلوا", "سفري", "دليفري"]):
            return {
                "reply": "أكيد يا غالي، التوصيل عندنا فوري وسريع لكافة مناطق وأحياء أمانة العاصمة صنعاء! 🛵💨\n\n"
                         "وجبتك توصلك ساخنة ومحبوكة مع الملوح المقمر والسحاوق كأنك جالس في المطعم.\n"
                         "اختر وجبتك أو خبرني كم عددكم وبنبدأ تجهيزها فوراً وتوصيلها لباب بيتك!",
                "bundle": [],
                "total_price": 0.0,
                "quick_replies": [
                    "كم نطلب لـ 2 إلى 3 أشخاص؟ 👥",
                    "عزومة 4 إلى 5 أشخاص 👨‍👩‍👧‍👦",
                    "طلب فردي لشخص واحد 👤"
                ]
            }

        # 6. فحص عدد الأشخاص أو طلب وجبة (كبيرة، صغيرة، عزيمة)
        person_count = persons_override or self.extract_person_count(msg)
        is_large_request = any(w in msg for w in ["وجبة كبيرة", "وجبه كبيره", "عزومة كبيرة", "عزيمة كبيرة", "سفرة كبيرة", "وليمة", "عزيمة", "عزومة"])

        if person_count or is_large_request:
            count = person_count or (6 if is_large_request else 2)
            bundle, total = self.compose_verified_meal(count)

            # تفصيل شرح محتوى السفرة باللهجة الصنعانية
            items_desc = "\n".join([f"• **{item['name']}** × {item['quantity']} ({item['price']:,.0f} ريال)" for item in bundle])

            if count == 1:
                explanation = "طلب فردي مشبع ومتوازن على الأصول (سمك ديرك مشوي على الجمر مع الخبز الملوح والسحاوق البلدي):"
            elif count in [2, 3]:
                explanation = f"لجمعتكم الطيبة ({count} أشخاص)، هذه التشكيلة المعتمدة الألذ والأشبع تجمع بين طراوة السخلة المشوية وقرمشة الجمبري وصانونة المدرة الساخنة:"
            elif count in [4, 5]:
                explanation = f"لعائلتكم الكريمة ({count} أشخاص)، سفرة ملكية وفيرة ترضي كل الأذواق تشمل المشوي والبروست وصانونة الجمبري والتحلية بالعريكة الملكية:"
            else:
                explanation = f"ما شاء الله تبارك الله، جمعة مباركة وعزيمة ترفع الراس ({count} أشخاص وما فوق)! جهزت لكم سفرة بحرية ملكية وفيرة تشبع الكل وتبيض وجهك:"

            reply = f"حيّاك الله وبياك يا غالي! 🐟👑\n\n" \
                    f"{explanation}\n\n" \
                    f"{items_desc}\n\n" \
                    f"💰 **الإجمالي المحسوب**: {total:,.0f} ريال يمني\n" \
                    f"✨ جميع الأصناف متوفرة وطازجة صيد اليوم ويمكنك إضافتها مباشرة لسلتك بنقرة زر أدناه!"

            return {
                "reply": reply,
                "bundle": bundle,
                "total_price": total,
                "quick_replies": [
                    "أضف التشكيلة كاملة للسلة 🛒",
                    "كم نطلب لـ 2 إلى 3 أشخاص؟ 👥",
                    "عزومة 4 إلى 5 أشخاص 👨‍👩‍👧‍👦",
                    "طلب فردي لشخص واحد 👤"
                ]
            }

        # 7. الاستفسار عن صيد اليوم والمنيو
        if any(w in msg for w in ["صيد اليوم", "ايش عندكم", "منيو", "قائمة", "اسماك", "ايش السمك"]):
            menu_lines = []
            for p in self.products[:8]:
                menu_lines.append(f"• **{p['name']}**: {p['price']:,.0f} ريال ({p.get('description', '')})")

            reply = "أبشر بأطزج وأشهى صيد بحري في صنعاء اليوم من البحر مباشرة لمخبازتنا! 🌊🐟\n\n" \
                    "من أبرز أطباقنا الحية المتوفرة اليوم:\n" + "\n".join(menu_lines) + "\n\n" \
                    "تفضل قولي كم عددكم الكرام أو نوع السمك اللي تشتهيه، وبضبط لك التشكيلة المضبوطة بالتمام!"

            return {
                "reply": reply,
                "bundle": [],
                "total_price": 0.0,
                "quick_replies": [
                    "كم نطلب لـ 2 إلى 3 أشخاص؟ 👥",
                    "عزومة 4 إلى 5 أشخاص 👨‍👩‍👧‍👦",
                    "عزيمة كبيرة (6 وما فوق) 👑",
                    "طلب فردي لشخص واحد 👤"
                ]
            }

        # 8. الرد الذكي الاستكشافي الودود
        return {
            "reply": "يا هلا بك يا غالي في مطعم نجوم اليمن للأسماك! 🐟✨\n\n"
                     "أنا رهن إشارتك لأي استفسار أو لترشيح أشهى تشكيلة بحرية من صيد اليوم الطازج.\n"
                     "تفضل قولي كم شخص أنتم؟ وسأجهز لك السفرة المثالية مع الملوح والسحاوق وكل ما تشتهيه نفسك!",
            "bundle": [],
            "total_price": 0.0,
            "quick_replies": [
                "كم نطلب لـ 2 إلى 3 أشخاص؟ 👥",
                "عزومة 4 إلى 5 أشخاص 👨‍👩‍👧‍👦",
                "عزيمة كبيرة (6 وما فوق) 👑",
                "طلب فردي لشخص واحد 👤",
                "ايش صيد اليوم عندكم؟ 🐟"
            ]
        }

if __name__ == "__main__":
    ai = YemenStarsChefAI()
    print("=== اختبار مودل مستشار نجوم اليمن ===")
    sample_queries = [
        "مرحبا",
        "طلبته وجبة كبيرة",
        "كم نطلب لـ 2 إلى 3 اشخاص؟",
        "وين وصل طلبي والسائق؟",
        "وين موقعكم بالضبط؟"
    ]
    for q in sample_queries:
        print(f"\n[سؤال العميل]: {q}")
        res = ai.generate_response(q)
        print(f"[رد المودل]:\n{res['reply']}")
        if res['bundle']:
            print(f"[الأصناف الحقيقية المضافة للسلة ({len(res['bundle'])} أصناف)]: {[item['name'] for item in res['bundle']]}")
            print(f"[الإجمالي]: {res['total_price']} ريال")
