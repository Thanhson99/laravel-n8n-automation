FROM python:3.11-slim-bookworm

WORKDIR /app

# Update system packages to patch vulnerabilities
RUN apt-get update && apt-get upgrade -y && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Python dependencies
COPY */requirements.txt /tmp/
RUN for req in /tmp/requirements.txt /tmp/*/requirements.txt; do \
        if [ -f "$req" ]; then \
            pip install --no-cache-dir -r "$req"; \
        fi; \
    done && pip install --no-cache-dir fastapi uvicorn

COPY . .

CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "5000"]
