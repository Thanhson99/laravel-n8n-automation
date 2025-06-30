<?php

namespace Tests\Feature\Controllers;

use App\Http\Controllers\CoinController;
use App\Services\Coin\CoinServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class CoinControllerTest extends TestCase
{
    public function testIndexReturnsViewWithCoins()
    {
        $mockService = $this->createMock(CoinServiceInterface::class);
        $mockService->method('getTopCoins')->willReturn([
            ['symbol' => 'BTCUSDT', 'price' => 30000]
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
