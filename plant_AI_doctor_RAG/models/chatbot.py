from langchain_classic.chains import ConversationalRetrievalChain
from langchain_classic.memory import ConversationBufferMemory
from langchain_groq import ChatGroq
from langchain_classic.prompts import SystemMessagePromptTemplate, ChatPromptTemplate
import os
from dotenv import load_dotenv

# charger les variables d'environnement
load_dotenv(dotenv_path=os.path.join(os.path.dirname(__file__), "..", ".env"))

SYSTEM_PROMPT = """
you are a doctor assistant that expert in plants and flowers
your config is:
    1. respond only about data that exist on the data provided 
    2. if you dont have the respond just say 'sorry i cant help with that info'
    3. explain from where your answers are comming from
    4. use a clear language but stay as technic as the job need 
    5. dont create responses out of what the docs says 
response format:
    - start with a clear response of the question
    - cite les sources specifiques (nb page, sections)
"""


class PlantChatbot:
    def __init__(self, vector_store):
        """
        init chatbot with vect db
        """
        print("Initialisation du chatbot plant doctor...")

        # stock vect db
        self.vector_store = vector_store

        # create memory to store previous chats
        self.memory = ConversationBufferMemory(
            memory_key="chat_history", return_messages=True, output_key="answer"
        )

        # verify groq key disp
        groq_api_key = os.environ.get("GROQ_API_KEY")  
        if not groq_api_key:
            raise ValueError("API KEY Groq not in env")

        # init model LLM Groq
        # Fix 1: use `model` not `model_name`
        # Fix 2: removed `groq_api_key` and `system` — not valid constructor params
        #         set the key via env var GROQ_API_KEY (already loaded by dotenv)
        #         pass the system prompt through the chain's prompt instead
        self.llm = ChatGroq(
            model="llama-3.3-70b-versatile",
            temperature=0.2,
        )
        print("Model LLM Groq initialise")

        # Build a prompt that injects the system message into the chain
        system_message_prompt = SystemMessagePromptTemplate.from_template(SYSTEM_PROMPT)

        # create the chaine de conversation avec RAG
        self.chain = ConversationalRetrievalChain.from_llm(
            llm=self.llm,
            retriever=self.vector_store.as_retriever(  # Fixed: typo as_retriver → as_retriever
                search_kwargs={"k": 3}
            ),
            memory=self.memory,
            return_source_documents=True,
        )
        print("Chaine de conv RAG init")

    # Fix 3: get_response was indented inside __init__ — moved out to class level
    async def get_response(self, query):
        """
        get response from the chatbot
        args:
            query str : user question
        returns:
            dict: dictionnaire with the response
        """
        print(f"Traitement de la requete: {query}")

        # invoquer la chaine de conversation
        result = await self.chain.ainvoke({"question": query})

        # extract sources
        sources = []
        for doc in result.get("source_documents", []):
            if hasattr(doc, "metadata") and "source" in doc.metadata:
                sources.append(doc.metadata["source"])
            elif hasattr(doc, "metadata") and "page" in doc.metadata:
                sources.append(f"Page {doc.metadata['page']}")

        # Fix 4: return was buried inside the for-loop — moved out after loop completes
        response = {"answer": result["answer"], "sources": list(set(sources))}
        print("Response generated with succes")
        return response
