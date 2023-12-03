import os
import uvicorn

if __name__ == "__mian__":
    uvicorn.run(
        "api:app",host="0.0.0.0", reload=True, port=int(os.environ.get("PORT",8080))
    )