<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Coin\FavoriteCoinRepository;
use App\Repositories\Coin\Interfaces\FavoriteCoinRepositoryInterface;
use App\Repositories\Coin\FeedKeywordRepository;
use App\Repositories\Coin\Interfaces\FeedKeywordRepositoryInterface;
use App\Repositories\Coin\Interfaces\TagRepositoryInterface;
use App\Repositories\Eloquent\TagRepository;
use App\Services\Coin\BinanceCoinApiClient;
use App\Services\Coin\CoinApiClientInterface;
use App\Services\Coin\CoinServiceFactory;
use App\Services\Coin\FavoriteCoinService;
use App\Services\Coin\FavoriteCoinServiceInterface;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 *
 * Responsible for registering and bootstrapping application services.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register all application service bindings.
     *
     * @return void
     */
    public function register(): void
    {
        // Bind the Coin API client interface to Binance implementation by default
        $this->app->bind(CoinApiClientInterface::class, BinanceCoinApiClient::class);

        // Bind the FavoriteCoin repository and service
        $this->app->bind(FavoriteCoinRepositoryInterface::class, FavoriteCoinRepository::class);
        $this->app->bind(FavoriteCoinServiceInterface::class, FavoriteCoinService::class);

        // Bind the Tag repository
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);

        $this->app->bind(FeedKeywordRepositoryInterface::class, FeedKeywordRepository::class);

        // Dynamically bind CoinServiceInterface based on the "source" query parameter
        $this->app->bind(
            \App\Services\Coin\CoinServiceInterface::class,
            function () {
                $source = Request::get('source', 'binance');

                return CoinServiceFactory::make($source);
            }
        );
    }

    /**
     * Bootstrap application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // No boot logic required for now
    }
}
