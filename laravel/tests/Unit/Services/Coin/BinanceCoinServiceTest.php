<?php

namespace Tests\Unit\Services\Coin;

use App\Services\Coin\BinanceCoinService;
use App\Services\Coin\CoinApiClientInterface;
use PHPUnit\Framework\TestCase;

class BinanceCoinServiceTest extends TestCase
{
    public function testGetTopCoinsReturnsData()
    {
        $mockClient = $this->createMock(CoinApiClientInterface::class);
        $mockClient->method('fetchTopCoins')
                   ->willReturn([['symbol' => 'BTCUSDT', 'price' => 30000]]);

        $service = new BinanceCoinService($mockClient);
        $result = $service->getTopCoins();

        $this->assertIsArray($result);
        $this->assertEquals('BTCUSDT', $result[0]['symbol']);
    }

    public function testGetCoinByIdReturnsCorrectCoin()
    {
        $mockClient = $this->createMock(CoinApiClientInterface::class);
        $mockClient->method('fetchCoinDetail')
                   ->with('BTCUSDT')
                   ->willReturn(['symbol' => 'BTCUSDT', 'price' => 30000]);

        $service = new BinanceCoinService($mockClient);
        $coin = $service->getCoinById('BTCUSDT');

        $this->assertIsArray($coin);
        $this->assertEquals('BTCUSDT', $coin['symbol']);
    }
}
