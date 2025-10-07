from fastapi import APIRouter

router = APIRouter()

# ---- Douyin route ----
@router.get("/douyin/hello")
def douyin_hello():
    return {"message": "Douyin Downloader service is running"}

# ---- AI Caption route ----
@router.get("/caption/hello")
def caption_hello():
    return {"message": "AI Video Caption service is running"}

# ---- Trending route ----
@router.get("/trending/hello")
def trending_hello():
    return {"message": "Trending Keywords service is running"}
