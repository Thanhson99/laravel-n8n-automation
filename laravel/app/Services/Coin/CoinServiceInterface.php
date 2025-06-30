<?php

namespace App\Services\Coin;

/**
 * Interface CoinServiceInterface
 *
 * Define methods for fetching coin data.
 */
interface CoinServiceInterface
{
    /**
     * Get a list of top coins with pricing info.
     *
     * @return array
     */
    public function getTopCoins(): array;

    /**
     * Get detailed info for a specific coin.
     *
     * @param string $coinId
     * @return array|null
     */
    public function getCoinById(string $coinId): ?array;
}
