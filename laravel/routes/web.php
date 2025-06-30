<?php

use App\Http\Controllers\CoinController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/coins', [CoinController::class, 'index'])->name('coins.index');
