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
     * @return array<int, array<string, mixed>>
     */
    public function getTopCoins(): array;

    /**
     * Get detailed info for a specific coin.
     *
     * @return array<string, mixed>
     */
    public function getCoinById(string $coinId): ?array;
}
