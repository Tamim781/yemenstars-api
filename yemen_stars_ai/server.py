#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""نقطة تشغيل توافقية؛ تمنع تشغيل المحرك القديم ذي الردود الثابتة."""
import uvicorn
from config import settings

if __name__ == "__main__":
    uvicorn.run("main:app", host=settings.HOST, port=settings.PORT, reload=False)
