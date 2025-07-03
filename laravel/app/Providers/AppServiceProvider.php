<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Coin\CoinServiceFactory;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 *
 * Registers and bootstraps application services and bindings.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind CoinApiClientInterface to default Binance implementation
        $this->app->bind(
            \App\Services\Coin\CoinApiClientInterface::class,
            \App\Services\Coin\BinanceCoinApiClient::class
        );

        // Bind FavoriteCoinRepositoryInterface to concrete repository
        $this->app->bind(
            \App\Repositories\FavoriteCoinRepositoryInterface::class,
            \App\Repositories\FavoriteCoinRepository::class
        );

        // Bind FavoriteCoinServiceInterface to its implementation
        $this->app->bind(
            \App\Services\Coin\FavoriteCoinServiceInterface::class,
            \App\Services\Coin\FavoriteCoinService::class
        );

        // Dynamically resolve CoinServiceInterface based on ?source= query param
        $this->app->bind(
            \App\Services\Coin\CoinServiceInterface::class,
            function () {
                $source = Request::get('source', 'binance');

                return CoinServiceFactory::make($source);
            }
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
