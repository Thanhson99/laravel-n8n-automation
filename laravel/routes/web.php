<?php

use App\Http\Controllers\Api\FavoriteCoinController;
use App\Http\Controllers\CoinController;
use App\Http\Controllers\FeedKeywordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome page
Route::view('/', 'welcome');

// Coin routes
Route::prefix('coins')->name('coins.')->controller(CoinController::class)->group(function () {
    Route::get('/', 'index')->name('index');                        // List top coins
    Route::get('show/{symbol}', 'show')->name('show');              // Show coin detail
});

// Feed keyword routes (under coins namespace)
Route::prefix('coins/feed-keywords')
    ->name('coins.feed-keywords.')
    ->controller(FeedKeywordController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');                    // Show keyword
        Route::post('store', 'store')->name('store');               // Handle keyword creation
    });

// Favorite toggle route (used via AJAX from Blade)
Route::post('favorites/toggle', [FavoriteCoinController::class, 'favoritesToggle'])->name('favorites.toggle');
