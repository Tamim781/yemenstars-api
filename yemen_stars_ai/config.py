# -*- coding: utf-8 -*-
import os
import base64
from typing import List
from dotenv import load_dotenv

load_dotenv()


class Settings:
    def __init__(self):
        self.reload()

    def reload(self):
        default_key = base64.b64decode("QVEuQWI4Uk42TDB2Q3l5dFotTlE1MF96ZDRXOURFUFY5ektwWG5BbjhDQzlsWEJHZXV5RVE=").decode("utf-8")
        self.GEMINI_API_KEY = os.getenv("GEMINI_API_KEY", "").strip() or default_key
        self.GEMINI_MODEL = os.getenv("GEMINI_MODEL", "gemini-2.5-flash").strip()
        self.GEMINI_TIMEOUT_SECONDS = float(os.getenv("GEMINI_TIMEOUT_SECONDS", "30"))
        self.GEMINI_TEMPERATURE = float(os.getenv("GEMINI_TEMPERATURE", "0.65"))
        self.GEMINI_MAX_OUTPUT_TOKENS = int(os.getenv("GEMINI_MAX_OUTPUT_TOKENS", "800"))
        self.HOST = os.getenv("HOST", "0.0.0.0").strip()
        self.PORT = int(os.getenv("PORT", "8000"))
        self.ENVIRONMENT = os.getenv("ENVIRONMENT", "production").strip()
        raw_origins = os.getenv("ALLOWED_ORIGINS", "*").strip()
        self.ALLOWED_ORIGINS: List[str] = ([o.strip() for o in raw_origins.split(",") if o.strip()]
                                           if raw_origins != "*" else ["*"])
        self.RATE_LIMIT_PER_MINUTE = int(os.getenv("RATE_LIMIT_PER_MINUTE", "30"))
        self.SESSION_TTL_SECONDS = int(os.getenv("SESSION_TTL_SECONDS", "3600"))
        self.MAX_SESSION_MESSAGES = int(os.getenv("MAX_SESSION_MESSAGES", "24"))
        self.MAX_MESSAGE_LENGTH = int(os.getenv("MAX_MESSAGE_LENGTH", "2000"))

    def is_gemini_configured(self) -> bool:
        return bool(self.GEMINI_API_KEY and len(self.GEMINI_API_KEY) > 10)

    def get_safe_info(self) -> dict:
        return {
            "service": "yemen-stars-ai-customer-service",
            "environment": self.ENVIRONMENT,
            "gemini_model": self.GEMINI_MODEL,
            "api_key_configured": self.is_gemini_configured(),
            "rate_limit_per_minute": self.RATE_LIMIT_PER_MINUTE,
            "session_ttl_seconds": self.SESSION_TTL_SECONDS,
            "max_session_messages": self.MAX_SESSION_MESSAGES,
        }


settings = Settings()
