<?php

declare(strict_types=1);

namespace App\Services\Coin;

use App\Repositories\Coin\FeedKeywordRepository;
use App\Repositories\Coin\TagRepository;

class FeedKeywordService
{
    protected FeedKeywordRepository $keywordRepo;
    protected TagRepository $tagRepo;

    public function __construct(
        FeedKeywordRepository $keywordRepo,
        TagRepository $tagRepo
    ) {
        $this->keywordRepo = $keywordRepo;
        $this->tagRepo = $tagRepo;
    }

    /**
     * Create keyword with tags
     */
    public function create(array $data): int
    {
        $id = $this->keywordRepo->create($data);

        if (!empty($data['tags'])) {
            $tagIds = $this->tagRepo->getOrCreateTags($data['tags']);
            $this->keywordRepo->syncTags($id, $tagIds);
        }

        return $id;
    }

    /**
     * Update keyword with tags
     */
    public function update(int $id, array $data): void
    {
        $this->keywordRepo->update($id, $data);

        if (!empty($data['tags'])) {
            $tagIds = $this->tagRepo->getOrCreateTags($data['tags']);
            $this->keywordRepo->syncTags($id, $tagIds);
        }
    }

    /**
     * Delete keyword
     */
    public function delete(int $id): void
    {
        $this->keywordRepo->delete($id);
    }
}
