<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedKeywordRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\Coin\FeedKeywordService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

/**
 * Class FeedKeywordController
 *
 * Handles creation of feed keywords and associated tags.
 */
class FeedKeywordController extends Controller
{
    /**
     * FeedKeywordController constructor.
     *
     * @param  FeedKeywordService  $keywordService
     */
    public function __construct(
        protected FeedKeywordService $keywordService
    ) {
    }

    /**
     * Show form to create a new feed keyword.
     *
     * @return View
     */
    public function index(): View
    {
        $keywords = $this->keywordService->getAllWithTags();
    
        return view('coins.keywords.index', compact('keywords'));
    }

    /**
     * Store a new feed keyword with optional tags.
     *
     * @param  StoreFeedKeywordRequest  $request  Validated request instance.
     * @return RedirectResponse
     */
    public function store(StoreFeedKeywordRequest $request): RedirectResponse
    {
        try {
            $this->keywordService->create($request->validated());
    
            return redirect()
                ->route('coins.feed-keywords.index')
                ->with('success', 'Keyword created successfully.');
        } catch (QueryException $e) {
            // Duplicate entry error
            if ($e->errorInfo[1] === 1062) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'This keyword already exists.');
            }
    
            Log::error('Database error on keyword creation: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Database error. Please try again later.');
        } catch (Exception $e) {
            Log::error('Failed to create keyword: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create keyword. Please try again later.');
        }
    }
}
