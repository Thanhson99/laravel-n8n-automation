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
     * @return array
     */
    public function fetchTopCoins(): array;

    /**
     * Get detailed info for a specific coin.
     *
     * @param string $coinId
     * @return array|null
     */
    public function fetchCoinDetail(string $coinId): ?array;
}
