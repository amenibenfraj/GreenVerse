#### Own code added
import sys
import os

sys.path.insert(0, os.path.dirname(__file__))

from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from pydantic import version
from routes.chatbot import router as chatbot_router


# creer lapp fastapi

app = FastAPI(
    title="Chatbot Plant doctor",
    description="API for a chatbot plants using Groq, \
    ChromaDB et miniLM",
    version="1.0.0",
    docs_url="/docs",
    redoc_url="/redoc",
)

# config CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# add routed for chatbot
app.include_router(chatbot_router, prefix="/api")


# Route racine
@app.get("/", tags=["root"])
async def root():
    # Route racine to  send welcome msg
    return {
        "message": "Welcome to API chatbot Plant doctor",
        "documentation": "/docs",
        "endpoints": {"upload_pdf": "/api/upload-pdf", "ask_question": "/api/ask"},
    }
