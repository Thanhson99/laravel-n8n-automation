<?php

namespace Tests\Unit\Services\Coin;

use App\Services\Coin\CoinServiceFactory;
use App\Services\Coin\BinanceCoinService;
use App\Services\Coin\CoinServiceInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoinServiceFactoryTest extends TestCase
{
    public function testMakeReturnsBinanceService()
    {
        $service = CoinServiceFactory::make('binance');

        $this->assertInstanceOf(CoinServiceInterface::class, $service);
        $this->assertInstanceOf(BinanceCoinService::class, $service);
    }

    public function testMakeThrowsExceptionForUnsupportedSource()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unsupported source [unknown]");

        CoinServiceFactory::make('unknown');
    }
}
