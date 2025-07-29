<?php

declare(strict_types=1);

namespace App\Repositories\Coin\Interfaces;

use App\Models\FeedKeyword;

interface FeedKeywordRepositoryInterface
{
    /**
     * Get a feed keyword by ID.
     *
     * @param  int  $id
     * @return FeedKeyword|null
     */
    public function find(int $id): ?FeedKeyword;

    /**
     * Get a feed keyword by its keyword string.
     *
     * @param  string  $keyword
     * @return FeedKeyword|null
     */
    public function findByKeyword(string $keyword): ?FeedKeyword;

    /**
     * Create a new feed keyword.
     *
     * @param  array<string, mixed>  $data
     * @return FeedKeyword
     */
    public function create(array $data): FeedKeyword;

    /**
     * Update an existing feed keyword by ID.
     *
     * @param  int  $id
     * @param  array<string, mixed>  $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a feed keyword by ID.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Sync tags for a feed keyword.
     *
     * @param  int  $keywordId
     * @param  array<int>  $tagIds
     * @return void
     */
    public function syncTags(int $keywordId, array $tagIds): void;
}
