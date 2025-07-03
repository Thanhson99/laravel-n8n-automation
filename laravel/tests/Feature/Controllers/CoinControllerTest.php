<?php

namespace Tests\Feature\Controllers;

use App\Services\Coin\CoinServiceInterface;
use Tests\TestCase;

class CoinControllerTest extends TestCase
{
    public function test_index_returns_view_with_coins()
    {
        $mockService = $this->createMock(CoinServiceInterface::class);
        $mockService->method('getTopCoins')->willReturn([
            ['symbol' => 'BTCUSDT', 'price' => 30000],
        ]);

        // Giả lập factory trả về mock
        $this->mock(\App\Services\Coin\CoinServiceFactory::class, function ($mock) use ($mockService) {
            $mock->shouldReceive('make')->with('binance')->andReturn($mockService);
        });

        $response = $this->get('/coins?source=binance');

        $response->assertStatus(200);
        $response->assertViewIs('coins.index');
        $response->assertViewHas('coins');
    }
}
