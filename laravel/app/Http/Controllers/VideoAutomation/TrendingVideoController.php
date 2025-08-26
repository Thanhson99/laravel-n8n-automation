<?php

declare(strict_types=1);

namespace App\Http\Controllers\VideoAutomation;

use App\Http\Controllers\Controller;
use App\Services\Python\PythonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Class TrendingVideoController
 *
 * Fetches trending Douyin videos for day/week/month and shows top 10 previews.
 */
class TrendingVideoController extends Controller
{
    private $pythonService;

    public function __construct(PythonService $pythonService)
    {
        $this->pythonService = $pythonService;
    }
    /**
     * Display trending Douyin videos.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $result = $this->pythonService->trendingKeywords();
        dd($result);
        return response()->json(json_decode($result, true));
    }
}
