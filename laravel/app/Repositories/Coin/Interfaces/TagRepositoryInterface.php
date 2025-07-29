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
     *
     * @param  int  $id
     * @return Tag|null
     */
    public function find(int $id): ?Tag;

    /**
     * Find a tag by name.
     *
     * @param  string  $name
     * @return Tag|null
     */
    public function findByName(string $name): ?Tag;

    /**
     * Create a new tag.
     *
     * @param  array<string, mixed>  $data
     * @return Tag
     */
    public function create(array $data): Tag;

    /**
     * Update a tag.
     *
     * @param  int  $id
     * @param  array<string, mixed>  $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a tag.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete(int $id): bool;
}
