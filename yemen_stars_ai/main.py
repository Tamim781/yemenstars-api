# -*- coding: utf-8 -*-
import logging
from contextlib import asynccontextmanager
from fastapi import FastAPI, Request, status
from fastapi.exceptions import RequestValidationError
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse

from config import settings
from knowledge import catalog_store
from schemas import ChatRequest, ChatResponse, HealthResponse
from services import gemini_service, rate_limiter, session_manager

logging.basicConfig(level=logging.INFO, format="%(asctime)s [%(levelname)s] %(name)s: %(message)s")
logger = logging.getLogger("yemen_stars_ai.main")


@asynccontextmanager
async def lifespan(app: FastAPI):
    logger.info("Starting Yemen Stars conversational AI")
    logger.info("Model=%s, catalog_products=%d", settings.GEMINI_MODEL, len(catalog_store.products))
    yield
    logger.info("Stopping Yemen Stars conversational AI")


app = FastAPI(
    title="Yemen Stars Conversational AI",
    description="Conversational seafood restaurant assistant powered by Gemini",
    version="3.0.0",
    lifespan=lifespan,
    docs_url="/docs" if settings.ENVIRONMENT != "production" else None,
    redoc_url=None,
)
app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.ALLOWED_ORIGINS,
    allow_credentials=settings.ALLOWED_ORIGINS != ["*"],
    allow_methods=["GET", "POST", "OPTIONS"],
    allow_headers=["*"]
)


@app.exception_handler(RequestValidationError)
async def validation_exception_handler(request: Request, exc: RequestValidationError):
    errors = exc.errors()
    detail = errors[0].get("msg", "البيانات المرسلة غير صحيحة.") if errors else "البيانات المرسلة غير صحيحة."
    return JSONResponse(status_code=status.HTTP_422_UNPROCESSABLE_ENTITY,
                        content={"status": "validation_error", "detail": detail})


@app.exception_handler(Exception)
async def generic_exception_handler(request: Request, exc: Exception):
    logger.error("Unhandled request error: %s", exc, exc_info=True)
    return JSONResponse(status_code=500, content={
        "status": "error",
        "detail": "حدث خطأ داخلي في الخادم. حاول لاحقاً."
    })


@app.get("/health", response_model=HealthResponse)
@app.get("/api/health", response_model=HealthResponse)
async def health_check():
    return HealthResponse(
        status="ok",
        service="yemen-stars-ai-customer-service",
        model=settings.GEMINI_MODEL,
        api_key_configured=settings.is_gemini_configured(),
        environment=settings.ENVIRONMENT,
    )


@app.get("/api/catalog")
async def get_catalog():
    return {
        "restaurant_info": catalog_store.restaurant_info,
        "products_count": len(catalog_store.products),
        "products": catalog_store.products,
        "fish_species": catalog_store.fish_species,
    }


@app.post("/api/chat", response_model=ChatResponse)
@app.post("/chat", response_model=ChatResponse)
async def chat_endpoint(request: Request, body: ChatRequest):
    client_ip = request.client.host if request.client else "unknown"
    rate_limiter.check(client_ip)
    session_id, history = session_manager.get_or_create_session(body.session_id)

    # لا توجد هنا مطابقة كلمات أو ردود محفوظة؛ كل رسالة تصل إلى Gemini مع سياقها.
    answer = await gemini_service.generate_reply(body.message, history, body.context)
    session_manager.append_turn(session_id, body.message, answer)
    return ChatResponse(answer=answer, session_id=session_id,
                        status="success" if answer else "error")


if __name__ == "__main__":
    import uvicorn
    uvicorn.run("main:app", host=settings.HOST, port=settings.PORT, reload=False)
