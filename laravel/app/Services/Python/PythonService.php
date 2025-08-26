<?php

namespace App\Services\Python;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * PythonService handles calling specific Python microservice endpoints.
 */
class PythonService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.python.base_url');
    }

    /**
     * Call Douyin downloader endpoint.
     *
     * @return array
     */
    public function downloadVideo(): array
    {
        $url = $this->baseUrl . config('services.python.douyin_path');

        return $this->call($url);
    }

    /**
     * Call AI Caption generator endpoint.
     *
     * @return array
     */
    public function generateCaption(): array
    {
        $url = $this->baseUrl . config('services.python.caption_path');

        return $this->call($url);
    }

    /**
     * Call Trending keywords endpoint.
     *
     * @return array
     */
    public function trendingKeywords(): array
    {
        $url = $this->baseUrl . config('services.python.trending_path');
        
        return $this->call($url);
    }

    /**
     * Generic HTTP GET call to Python service.
     *
     * @param string $url
     * @return array
     */
    protected function call(string $url): array
    {
        $response = Http::get($url);

        if ($response->failed()) {
            // log error
            Log::error("Python service call failed: $url", $response->json());
            return [];
        }

        return $response->json();
    }
}
