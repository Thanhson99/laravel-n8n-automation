<?php

declare(strict_types=1);

namespace App\Http\Controllers\VideoAutomation;

use App\Http\Controllers\Controller;
use App\Services\Python\PythonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     */
    public function index(Request $request): JsonResponse
    {
        /** @var array<string, mixed> $result */
        $result = $this->pythonService->trendingKeywords();

        return response()->json($result);
    }
}
