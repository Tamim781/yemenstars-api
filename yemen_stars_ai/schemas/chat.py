# -*- coding: utf-8 -*-
from typing import Any, Dict, Optional
import re
from pydantic import BaseModel, Field, field_validator


class ChatRequest(BaseModel):
    message: str = Field(..., min_length=1, max_length=2000)
    session_id: Optional[str] = Field(None, max_length=96)
    context: Optional[Dict[str, Any]] = Field(default=None, description="سياق واجهة غير سري اختياري")

    @field_validator("message")
    @classmethod
    def validate_message(cls, value: str) -> str:
        cleaned = re.sub(r'[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]', '', value.strip())
        if not cleaned:
            raise ValueError("لا يمكن أن تكون الرسالة فارغة.")
        return cleaned

    @field_validator("session_id")
    @classmethod
    def validate_session_id(cls, value: Optional[str]) -> Optional[str]:
        if value is None or not value.strip():
            return None
        value = value.strip()
        if not re.fullmatch(r'[a-zA-Z0-9_-]{1,96}', value):
            raise ValueError("معرف الجلسة غير صالح.")
        return value

    @field_validator("context")
    @classmethod
    def validate_context(cls, value: Optional[Dict[str, Any]]) -> Optional[Dict[str, Any]]:
        if value is not None and len(str(value)) > 4000:
            raise ValueError("سياق المحادثة كبير جداً.")
        return value


class ChatResponse(BaseModel):
    answer: str
    session_id: str
    status: str = "success"


class HealthResponse(BaseModel):
    status: str
    service: str
    model: str
    api_key_configured: bool
    environment: str
