# -*- coding: utf-8 -*-
"""
اختبارات عزل الجلسات، صمود النظام أمام مهلة وفشل Gemini، والحد من الطلبات
"""

import asyncio
import pytest
from unittest.mock import MagicMock, patch
from fastapi.testclient import TestClient
import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).parent.parent))

from main import app
from services import session_manager, gemini_service, rate_limiter

client = TestClient(app)

class DummyGeminiResponse:
    def __init__(self, text: str):
        self.text = text

def test_session_isolation_between_clients():
    """
    اختبار عزل الجلسات: التحقق من أن كل عميل له سياق مستقل تماماً
    ولا يمكن لعميل رؤية أو التأثير على سياق عميل آخر.
    """
    mock_client = MagicMock()
    mock_client.models.generate_content.return_value = DummyGeminiResponse("رد مخصص")

    original = gemini_service._custom_client
    gemini_service._custom_client = mock_client

    try:
        # عميل أ يبدأ جلسة
        res_a = client.post("/api/chat", json={"message": "أنا العميل أحمد وأطلب ديرك"})
        sid_a = res_a.json()["session_id"]

        # عميل ب يبدأ جلسة منفصلة
        res_b = client.post("/api/chat", json={"message": "أنا العميل خالد وأطلب جمبري"})
        sid_b = res_b.json()["session_id"]

        # التأكد من اختلاف معرف الجلسة
        assert sid_a != sid_b

        # التحقق من أن سجل الجلسة أ يحتوي على رسائل أحمد فقط
        _, history_a = session_manager.get_or_create_session(sid_a)
        assert any("أحمد" in m["text"] for m in history_a)
        assert not any("خالد" in m["text"] for m in history_a)

        # التحقق من أن سجل الجلسة ب يحتوي على رسائل خالد فقط
        _, history_b = session_manager.get_or_create_session(sid_b)
        assert any("خالد" in m["text"] for m in history_b)
        assert not any("أحمد" in m["text"] for m in history_b)

    finally:
        gemini_service._custom_client = original

def test_unauthorized_or_invalid_session_id_gets_new_secure_session():
    """
    اختبار منع تخمين أو اختراق معرفات الجلسات (Session Tampering Defense):
    إذا أرسل المستخدم معرف جلسة عشوائي غير موجود أو غير صالح، يتم منحه جلسة جديدة نظيفة.
    """
    tampered_id = "non_existent_fake_session_12345"
    mock_client = MagicMock()
    mock_client.models.generate_content.return_value = DummyGeminiResponse("أهلاً بك")

    original = gemini_service._custom_client
    gemini_service._custom_client = mock_client

    try:
        res = client.post("/api/chat", json={"message": "مرحبا", "session_id": tampered_id})
        assert res.status_code == 200
        new_sid = res.json()["session_id"]
        # يجب توليد معرف آمن جديد وعدم استخدام المعرف المجهول
        assert new_sid.startswith("sess_")
        assert new_sid != tampered_id
    finally:
        gemini_service._custom_client = original

def test_gemini_timeout_handling():
    """
    اختبار صمود الخادم عند حدوث مهلة زمنية (Timeout) في الاتصال بـ Gemini
    يجب أن يعيد الخادم رسالة واضحة للمستخدم دون توقف الخدمة.
    """
    # محاكاة حدوث Timeout
    with patch("asyncio.wait_for", side_effect=asyncio.TimeoutError()):
        mock_client = MagicMock()
        original = gemini_service._custom_client
        gemini_service._custom_client = mock_client

        try:
            res = client.post("/api/chat", json={"message": "وجبة مشكلة"})
            assert res.status_code == 200
            data = res.json()
            assert data["status"] == "success"
            assert "استغرق الرد" in data["answer"]
        finally:
            gemini_service._custom_client = original

def test_gemini_exception_handling():
    """
    اختبار معالجة استثناءات خطأ الاتصال بـ Gemini (مثل انقطاع الإنترنت أو كوتا منتهية)
    """
    mock_client = MagicMock()
    mock_client.models.generate_content.side_effect = RuntimeError("Resource exhausted 429 quota")

    original = gemini_service._custom_client
    gemini_service._custom_client = mock_client

    try:
        res = client.post("/api/chat", json={"message": "كم سعر الجمبري؟"})
        assert res.status_code == 200
        data = res.json()
        assert data["status"] == "success"
        assert "تعذر الاتصال" in data["answer"]
        # التأكد من عدم تسريب رسالة الخطأ الأصلية Resource exhausted
        assert "Resource exhausted" not in res.text
    finally:
        gemini_service._custom_client = original

def test_rate_limiter_throttling():
    """
    اختبار الحد من الطلبات (Rate Limiting) عند إرسال طلبات مكثفة
    """
    test_limiter = rate_limiter.__class__(limit_per_minute=3)
    with patch("main.rate_limiter", test_limiter):
        mock_client = MagicMock()
        mock_client.models.generate_content.return_value = DummyGeminiResponse("رد سريع")
        original = gemini_service._custom_client
        gemini_service._custom_client = mock_client

        try:
            # أول 3 طلبات تنجح
            for _ in range(3):
                r = client.post("/api/chat", json={"message": "سؤال"})
                assert r.status_code == 200

            # الطلب الرابع يجب أن يُرفض برمز 429
            r4 = client.post("/api/chat", json={"message": "سؤال متكرر"})
            assert r4.status_code == 429
            assert "تجاوز الحد الأقصى" in r4.json()["detail"]
        finally:
            gemini_service._custom_client = original
