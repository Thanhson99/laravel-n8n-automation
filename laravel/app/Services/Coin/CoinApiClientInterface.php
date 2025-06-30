<?php

namespace App\Services\Coin;

/**
 * Interface CoinApiClientInterface
 *
 * Interface for calling external coin APIs.
 */
interface CoinApiClientInterface
{
    /**
     * Get market data for top coins.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchTopCoins(): array;

    /**
     * Get detailed info for a specific coin.
     *
     * @return array<string, mixed>
     */
    public function fetchCoinDetail(string $coinId): ?array;
}
