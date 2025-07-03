<?php

namespace App\Http\Controllers;

use App\Services\Coin\CoinServiceFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
        $rawSource = $request->get('source', 'binance');
        $source = is_string($rawSource) ? $rawSource : 'binance';
        $coinService = CoinServiceFactory::make($source);

        $coins = $coinService->getTopCoins();

        return view('coins.index', compact('coins', 'source'));
    }
}
