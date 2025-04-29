from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import List
import os
from sentence_transformers import SentenceTransformer
import numpy as np
import openai
from dotenv import load_dotenv

load_dotenv()

app = FastAPI()

# Initialize models
embedding_model = SentenceTransformer('all-MiniLM-L6-v2')
openai.api_key = os.getenv("OPENAI_API_KEY")

class Document(BaseModel):
    id: int
    title: str
    content: str

class QueryRequest(BaseModel):
    question: str
    documents: List[Document]

def get_embeddings(texts):
    return embedding_model.encode(texts, convert_to_tensor=True)

def semantic_search(query_embedding, doc_embeddings, documents, top_k=3):
    # Compute cosine similarity
    scores = np.dot(doc_embeddings, query_embedding.T).flatten()
    
    # Get top K documents
    top_indices = np.argsort(scores)[-top_k:][::-1]
    return [(documents[i].content, scores[i]) for i in top_indices]

def generate_answer_with_context(question, context_docs):
    context = "\n\n".join([doc for doc, _ in context_docs])
    
    response = openai.ChatCompletion.create(
        model="gpt-4",
        messages=[
            {"role": "system", "content": "You are a helpful assistant that answers questions based on the provided context. If the answer isn't in the context, say you don't know."},
            {"role": "user", "content": f"Context:\n{context}\n\nQuestion: {question}\nAnswer:"}
        ],
        temperature=0.7,
        max_tokens=500
    )
    
    return response.choices[0].message.content

@app.post("/query-policies")
async def query_policies(request: QueryRequest):
    try:
        # Get embeddings for all documents
        doc_texts = [doc.content for doc in request.documents]
        doc_embeddings = get_embeddings(doc_texts)
        
        # Get embedding for the question
        question_embedding = get_embeddings([request.question])[0]
        
        # Perform semantic search
        relevant_docs = semantic_search(
            question_embedding, 
            doc_embeddings, 
            request.documents
        )
        
        # Generate answer using the most relevant documents
        answer = generate_answer_with_context(request.question, relevant_docs)
        
        return {"answer": answer}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)