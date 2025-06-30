<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Services\Coin\CoinApiClientInterface::class,
            \App\Services\Coin\BinanceCoinApiClient::class
        );
        
        $this->app->bind(
            \App\Services\Coin\CoinServiceInterface::class,
            \App\Services\Coin\BinanceCoinService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
