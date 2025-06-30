<?php

namespace App\Services\Coin;

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
     */
    public function __construct(CoinApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get a list of top coins with pricing info.
     */
    public function getTopCoins(): array
    {
        return $this->client->fetchTopCoins();
    }

    /**
     * Get detailed info for a specific coin.
     */
    public function getCoinById(string $coinId): ?array
    {
        return $this->client->fetchCoinDetail($coinId);
    }
}
