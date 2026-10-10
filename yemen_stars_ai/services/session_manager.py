# -*- coding: utf-8 -*-
import re
import threading
import time
import uuid
from typing import Dict, List, Optional, Tuple
from config import settings


class SessionManager:
    def __init__(self, ttl_seconds: int = 3600, max_messages: int = 24):
        self.ttl_seconds = ttl_seconds
        self.max_messages = max_messages
        self._sessions: Dict[str, Dict] = {}
        self._lock = threading.RLock()

    def _cleanup_expired(self) -> None:
        now = time.time()
        for sid in [sid for sid, data in self._sessions.items()
                    if now - data.get("last_active", 0) > self.ttl_seconds]:
            self._sessions.pop(sid, None)

    def get_or_create_session(self, session_id: Optional[str]) -> Tuple[str, List[Dict[str, str]]]:
        with self._lock:
            self._cleanup_expired()
            now = time.time()
            if session_id and re.fullmatch(r'[a-zA-Z0-9_-]{1,96}', session_id):
                data = self._sessions.get(session_id)
                if data and now - data["last_active"] <= self.ttl_seconds:
                    data["last_active"] = now
                    return session_id, list(data["history"])
            new_sid = f"sess_{uuid.uuid4().hex}"
            self._sessions[new_sid] = {"last_active": now, "history": []}
            return new_sid, []

    def append_turn(self, session_id: str, user_text: str, model_text: str) -> None:
        with self._lock:
            now = time.time()
            session = self._sessions.setdefault(session_id, {"last_active": now, "history": []})
            session["last_active"] = now
            # لا تحفظ أرقام البطاقات أو رموز التحقق في ذاكرة الحوار.
            safe_user = re.sub(r'\b(?:\d[ -]?){13,19}\b', '[REDACTED_PAYMENT]', user_text)
            safe_user = re.sub(r'\b(?:otp|رمز التحقق)\s*[:：]?\s*\d{4,8}\b', '[REDACTED_CODE]', safe_user, flags=re.I)
            session["history"].extend([
                {"role": "user", "text": safe_user},
                {"role": "model", "text": model_text},
            ])
            session["history"] = session["history"][-self.max_messages:]

    def clear_session(self, session_id: str) -> bool:
        with self._lock:
            return self._sessions.pop(session_id, None) is not None

    def active_sessions_count(self) -> int:
        with self._lock:
            self._cleanup_expired()
            return len(self._sessions)


session_manager = SessionManager(settings.SESSION_TTL_SECONDS, settings.MAX_SESSION_MESSAGES)
