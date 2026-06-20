from langchain_community.document_loaders import PyPDFLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_community.vectorstores import Chroma
from langchain_community.embeddings import HuggingFaceEmbeddings
import os
from dotenv import load_dotenv


# charger les variables d'envirenement
load_dotenv()


def load_and_process_pdf(pdf_path, original_name=None):
    """
    14 Charge un document PDF , le divise en chunks et cr ́ee une base de
    donn  ́ees vectorielle avec ChromaDB .
    15
    16 Args :
    17 pdf_path ( str ): Chemin vers le fichier PDF `a traiter
    18
    19 Returns :
    20 Chroma : Base de donn  ́ees vectorielle contenant les chunks du
    document
    """

    print(f"Chargement du PDF:{pdf_path}")

    # Charger le pdf avec pypdfloader
    loader = PyPDFLoader(pdf_path)
    documents = loader.load()
    print(f"PDF charger avec succes: {len(documents)} pages")

    # diviser le text en chunks plus petits pour un melleur traitement

    text_splitter = RecursiveCharacterTextSplitter(
        chunk_size=500, chunk_overlap=100, length_function=len
    )
    chunks = text_splitter.split_documents(documents)
    print(f"Document divise en {len(chunks)} chunks")

    # initialiser le model minlm
    embeddings = HuggingFaceEmbeddings(
        model_name="sentence-transformers/all-MiniLM-L6-v2",
        model_kwargs={"device": "cpu"},
    )
    print("Model d'embedding MiniLM initialise")

    # creer une base de donnees vect ChromaDB
    db_directory = "./data/chroma_db"
    vector_store = Chroma.from_documents(
        documents=chunks, embedding=embeddings, persist_directory=db_directory
    )

    vector_store.persist()
    print(f" Base de donnees vectorielle ChromaDB cree et persist  ́dans {db_directory}")
    return vector_store
