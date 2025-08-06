<?php

declare(strict_types=1);

namespace App\Repositories\Coin\Interfaces;

use App\Models\FeedKeyword;

interface FeedKeywordRepositoryInterface
{
    /**
     * Get a feed keyword by ID.
     */
    public function find(int $id): ?FeedKeyword;

    /**
     * Get a feed keyword by its keyword string.
     */
    public function findByKeyword(string $keyword): ?FeedKeyword;

    /**
     * Create a new feed keyword.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): FeedKeyword;

    /**
     * Update an existing feed keyword by ID.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a feed keyword by ID.
     */
    public function delete(int $id): bool;

    /**
     * Sync tags for a feed keyword.
     *
     * @param  array<int>  $tagIds
     */
    public function syncTags(int $keywordId, array $tagIds): void;
}
