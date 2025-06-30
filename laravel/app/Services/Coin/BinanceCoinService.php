<?php

namespace App\Services\Coin;

use App\Services\Coin\CoinServiceInterface;
use App\Services\Coin\CoinApiClientInterface;

/**
 * Class BinanceCoinService
 *
 * Service for handling coin data from Binance.
 */
class BinanceCoinService implements CoinServiceInterface
{
    protected CoinApiClientInterface $client;

    /**
     * BinanceCoinService constructor.
     *
     * @param CoinApiClientInterface $client
     */
    public function __construct(CoinApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get a list of top coins with pricing info.
     *
     * @return array
     */
    public function getTopCoins(): array
    {
        return $this->client->fetchTopCoins();
    }

    /**
     * Get detailed info for a specific coin.
     *
     * @param string $coinId
     * @return array|null
     */
    public function getCoinById(string $coinId): ?array
    {
        return $this->client->fetchCoinDetail($coinId);
    }
}
