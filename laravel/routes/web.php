<?php

use App\Http\Controllers\Api\FavoriteCoinController;
use App\Http\Controllers\CoinController;
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
    Route::get('/', 'index')->name('index');                                // List top coins
    Route::get('{symbol}', 'show')->name('show');                           // Show coin detail
});

// Favorite toggle route (used via AJAX from Blade)
Route::post('favorites/toggle', [FavoriteCoinController::class, 'favoritesToggle'])->name('favorites.toggle');
