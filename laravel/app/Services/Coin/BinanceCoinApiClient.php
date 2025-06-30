<?php

namespace App\Services\Coin;

use App\Services\Coin\CoinApiClientInterface;
use Illuminate\Support\Facades\Http;

/**
 * Class BinanceCoinApiClient
 *
 * Handles requests to Binance public API.
 */
class BinanceCoinApiClient implements CoinApiClientInterface
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.binance.base_url', 'https://api.binance.com');
    }

    /**
     * Get market data for top coins.
     *
     * @return array
     */
    public function fetchTopCoins(): array
    {
        $response = Http::get($this->baseUrl . '/api/v3/ticker/24hr');

        if ($response->successful()) {
            return collect($response->json())
                ->sortByDesc('quoteVolume')
                ->take(10)
                ->values()
                ->toArray();
        }

        return [];
    }

    /**
     * Get detailed info for a specific coin.
     *
     * @param string $coinId
     * @return array|null
     */
    public function fetchCoinDetail(string $coinId): ?array
    {
        $response = Http::get($this->baseUrl . '/api/v3/ticker/24hr', [
            'symbol' => strtoupper($coinId),
        ]);

        return $response->successful() ? $response->json() : null;
    }
}
