# -*- coding: utf-8 -*-
"""
محدد معدل الطلبات (Rate Limiter)
يحمي الـ API من الإغراق وإساءة الاستخدام بناءً على عنوان العميل IP أو الجلسة.
"""

import time
from collections import defaultdict
from typing import Dict, List
from fastapi import HTTPException, status
from config import settings

class RateLimiter:
    def __init__(self, limit_per_minute: int = 30):
        self.limit_per_minute = limit_per_minute
        self._requests: Dict[str, List[float]] = defaultdict(list)

    def check(self, key: str) -> None:
        """
        التحقق من عدم تجاوز معدل الطلبات خلال آخر دقيقة.
        في حال التجاوز يتم رفع خطأ 429 Too Many Requests.
        """
        now = time.time()
        window_start = now - 60.0

        # تصفية الطلبات القديمة الأقدم من 60 ثانية
        recent_timestamps = [ts for ts in self._requests[key] if ts > window_start]
        self._requests[key] = recent_timestamps

        if len(recent_timestamps) >= self.limit_per_minute:
            raise HTTPException(
                status_code=status.HTTP_429_TOO_MANY_REQUESTS,
                detail="تم تجاوز الحد الأقصى للطلبات في الدقيقة الواحدة. فضلاً انتظر لحظات ثم حاول مجدداً."
            )

        self._requests[key].append(now)

rate_limiter = RateLimiter(limit_per_minute=settings.RATE_LIMIT_PER_MINUTE)
