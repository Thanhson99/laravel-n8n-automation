<?php

namespace App\Http\Controllers;

use App\Services\Coin\CoinServiceFactory;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class CoinController
 *
 * Handles display of coin list and watchlist actions.
 */
class CoinController extends Controller
{
    /**
     * Display a list of popular coins from selected source.
     */
    public function index(Request $request): View
    {
        // Default is binance
        $source = $request->get('source', 'binance');
        $coinService = CoinServiceFactory::make($source);

        $coins = $coinService->getTopCoins();

        return view('coins.index', compact('coins', 'source'));
    }
}
