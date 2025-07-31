<?php

declare(strict_types=1);

namespace App\Services\Coin;

use App\Repositories\Coin\FeedKeywordRepository;
use App\Repositories\Coin\TagRepository;
use App\Models\FeedKeyword;

class FeedKeywordService
{
    /**
     * FeedKeywordService constructor.
     *
     * @param FeedKeywordRepositoryInterface $keywordRepo
     * @param TagRepositoryInterface $tagRepo
     */
    public function __construct(
        protected FeedKeywordRepository $keywordRepo,
        protected TagRepository $tagRepo
    ) {
    }

    /**
     * Create a new feed keyword with associated tags.
     *
     * @param array<string, mixed> $data
     * @return int  The ID of the created feed keyword.
     */
    public function create(array $data): int
    {
        /** @var FeedKeyword $keyword */
        $keyword = $this->keywordRepo->create($data);

        if (!empty($data['tags']) && is_array($data['tags'])) {
            $tagIds = $this->tagRepo->getOrCreateTags($data['tags']);
            $this->keywordRepo->syncTags($keyword->id, $tagIds);
        }

        return $keyword->id;
    }

    /**
     * Update an existing feed keyword and sync its tags.
     *
     * @param int $id  Feed keyword ID.
     * @param array<string, mixed> $data  Updated data.
     * @return void
     */
    public function update(int $id, array $data): void
    {
        $this->keywordRepo->update($id, $data);

        if (!empty($data['tags']) && is_array($data['tags'])) {
            $tagIds = $this->tagRepo->getOrCreateTags($data['tags']);
            $this->keywordRepo->syncTags($id, $tagIds);
        }
    }

    /**
     * Delete a feed keyword by its ID.
     *
     * @param int $id  Feed keyword ID.
     * @return void
     */
    public function delete(int $id): void
    {
        $this->keywordRepo->delete($id);
    }

    /**
     * Get all feed keywords with tags.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithTags()
    {
        return $this->keywordRepo->allWithTags();
    }
}
