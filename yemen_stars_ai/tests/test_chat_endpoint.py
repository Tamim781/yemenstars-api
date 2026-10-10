# -*- coding: utf-8 -*-
"""
اختبارات وحدة لـ POST /api/chat مع Mock لعميل Gemini
تثبت أن endpoint يستدعي العميل ويرجع الرد دون ردود جاهزة وهمية.
"""

import pytest
from unittest.mock import MagicMock
from fastapi.testclient import TestClient
import sys
from pathlib import Path

# إضافة مسار المشروع للمسار العام
sys.path.insert(0, str(Path(__file__).parent.parent))

from main import app
from services import gemini_service, session_manager

client = TestClient(app)

class DummyGeminiResponse:
    def __init__(self, text: str):
        self.text = text

def test_chat_endpoint_calls_gemini_client_and_returns_reply():
    """
    اختبار آلي يثبت أن endpoint يستدعي عميل Gemini ويرجع الرد الذي أعاده العميل (بواسطة Mock)
    """
    mock_client = MagicMock()
    mock_client.models.generate_content.return_value = DummyGeminiResponse(
        "أهلاً بك يا غالي! سمك السخلة المشوي متوفر بسعر 7500 ريال للكيلو صيد طازج."
    )

    # حقن الـ Mock في خدمة Gemini
    original_client = gemini_service._custom_client
    gemini_service._custom_client = mock_client

    try:
        payload = {
            "message": "كم سعر سمك السخلة المشوي؟",
        }
        res = client.post("/api/chat", json=payload)
        assert res.status_code == 200
        data = res.json()

        # التحقق من أن النتيجة مطابقة لرد عميل Gemini
        assert data["status"] == "success"
        assert "7500 ريال" in data["answer"]
        assert "session_id" in data
        assert data["session_id"].startswith("sess_")

        # التحقق من أن العميل استُدعي فعلياً
        assert mock_client.models.generate_content.called
        call_kwargs = mock_client.models.generate_content.call_args[1]
        assert "contents" in call_kwargs
        assert "config" in call_kwargs

    finally:
        gemini_service._custom_client = original_client

def test_chat_maintains_session_across_turns():
    """
    اختبار استمرارية الجلسة وسياق المحادثة عبر session_id
    """
    mock_client = MagicMock()
    mock_client.models.generate_content.return_value = DummyGeminiResponse(
        "السفرة المقترحة لشخصين هي سخلة مشوية وصانونة جمبري."
    )

    original_client = gemini_service._custom_client
    gemini_service._custom_client = mock_client

    try:
        # الرسالة الأولى
        res1 = client.post("/api/chat", json={"message": "نحن شخصين"})
        assert res1.status_code == 200
        sid = res1.json()["session_id"]

        # الرسالة الثانية مع نفس معرف الجلسة
        res2 = client.post("/api/chat", json={"message": "وما هي المقبلات؟", "session_id": sid})
        assert res2.status_code == 200
        assert res2.json()["session_id"] == sid

        # فحص محتويات الاستدعاء الثاني للتأكد من وصول السجل السابق للنموذج
        call_args = mock_client.models.generate_content.call_args[1]
        contents = call_args["contents"]
        # يجب أن يحتوي على أكثر من عنصر (سجل الرسائل السابقة + الرسالة الحالية)
        assert len(contents) >= 3

    finally:
        gemini_service._custom_client = original_client

def test_empty_message_rejected():
    """اختبار رفض الرسائل الفارغة برمز 422"""
    res = client.post("/api/chat", json={"message": ""})
    assert res.status_code == 422
    assert "detail" in res.json()

def test_whitespace_only_message_rejected():
    """اختبار رفض الرسائل المكونة من مسافات فقط برمز 422"""
    res = client.post("/api/chat", json={"message": "     "})
    assert res.status_code == 422

def test_oversized_message_rejected():
    """اختبار رفض الرسائل الطويلة جداً التي تتجاوز 500 حرف"""
    huge_message = "سمك " * 600  # يتجاوز 2000 حرف
    res = client.post("/api/chat", json={"message": huge_message})
    assert res.status_code == 422
    assert "detail" in res.json()
