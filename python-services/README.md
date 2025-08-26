python-services/
│
├── main.py
│
├── tool-download-video-douyin/         # Service 1 - Douyin video downloader
│   ├── main.py                         # Entry point
│   ├── downloader/                     # Video download logic
│   ├── scraper/                        # Crawl video links from Douyin
│   ├── utils/                          # Helper functions (parse, format, validate)
│   ├── views/                          # API or CLI interfaces
│   ├── requirements.txt                # Dependencies for this service
│   ├── README.md                       # Service documentation
│   └── __init__.py                     # Mark package as Python module
│
├── tool-ai-video-caption/              # Service 2 - AI video caption generator
│   ├── main.py
│   ├── caption_generator/              # Caption generation logic
│   ├── utils/
│   ├── requirements.txt
│   ├── README.md
│   └── __init__.py
│
├── tool-trending-keywords/             # Service 3 - Trending keywords crawler
│   ├── main.py
│   ├── scraper/                        # Crawl trending keywords
│   ├── utils/
│   ├── requirements.txt
│   ├── README.md
│   └── __init__.py
│
└── shared-libs/                        # Shared libraries
    ├── logger.py                       # Standard logging for all services
    ├── http_client.py                  # HTTP requests wrapper
    ├── config.py                       # Load config from .env
    ├── __init__.py
    └── constants.py                    # Common constants
