<?php

declare(strict_types=1);

namespace App\Repositories\Coin\Interfaces;

use App\Models\Tag;

interface TagRepositoryInterface
{
    /**
     * Get all tags.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Tag>
     */
    public function all();

    /**
     * Find a tag by ID.
     */
    public function find(int $id): ?Tag;

    /**
     * Find a tag by name.
     */
    public function findByName(string $name): ?Tag;

    /**
     * Create a new tag.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Tag;

    /**
     * Update a tag.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a tag.
     */
    public function delete(int $id): bool;

    /**
     * Get existing tag IDs or create them if not exist.
     *
     * @param  array<string>  $tagNames
     * @return array<int> Tag IDs.
     */
    public function getOrCreateTags(array $tagNames): array;
}
