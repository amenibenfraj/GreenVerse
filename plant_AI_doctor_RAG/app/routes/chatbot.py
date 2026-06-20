from fastapi import APIRouter, UploadFile, File, HTTPException
from fastapi.responses import JSONResponse
import os
import tempfile
from pydantic import BaseModel
from typing import List, Optional
from models.chatbot import PlantChatbot
from utils.pdf_loader import load_and_process_pdf

# creating router
router = APIRouter(tags=["chatbot"])


# models de donne pour validation IO
class Query(BaseModel):
    text: str

    class Config:
        schema_extra = {
            "example": {"text": "Quelles sont les cause des feille jeune d'une pomme"}
        }


class Response(BaseModel):
    answer: str
    sources: Optional[List[str]] = None


# variable globale pour stocker l'instance du chatbot
chatbot_instance = None


@router.post(
    "/upload-pdf", response_class=JSONResponse, summary="Telecharger un document PDF"
)
async def upload_pdf(file: UploadFile = File(...)):
    """
    Telecharger un document PDF et initialise le chatbot des PlantChatbot
    - **file**: fichier PDF a telecharger (format.pdf only)
    return msg de confirmation si chargemet reussie
    """
    global chatbot_instance

    # validate the file is pdf
    if not file.filename.endswith(".pdf"):
        raise HTTPException(
            status_code=400, detail="le fichier doit etre au format pdf (.pdf)"
        )

    # creer un fichier temporaire for stocker pdf
    with tempfile.NamedTemporaryFile(delete=False, suffix=".pdf") as temp_file:
        # read contenet from downloaded file read to temp file
        file_content = await file.read()
        temp_file.write(file_content)
        temp_file_path = temp_file.name
    try:
        # charger et traitter le pdf with utilitaire
        vector_store = load_and_process_pdf(temp_file_path, original_name=file.filename)
        # init chatbot with vect db
        chatbot_instance = PlantChatbot(vector_store)
        return {
            "message": "Document PDF charge avec succes et chatbot init",
            "filename": file.filename,
        }
    except Exception as e:
        raise HTTPException(
            status_code=500, detail=f"Error while traitting PDF :{str(e)}"
        )
    finally:
        # remove temp file
        if os.path.exists(temp_file_path):
            os.unlink(temp_file_path)


@router.post("/ask", response_model=Response, summary="Poser une question au chatbot")
async def ask_question(query: Query):
    global chatbot_instance

    # verify chatbot exists
    if chatbot_instance is None:
        raise HTTPException(
            status_code=400,
            detail="veuillez d'abors telecharger un document pdf via /upload-pdf",
        )

    try:
        response = await chatbot_instance.get_response(query.text)
        return response
    except Exception as e:
        raise HTTPException(
            status_code=500,
            detail=f"Erreur lors de la generation de la response:{str(e)}",
        )
