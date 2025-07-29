<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Tag;
use App\Repositories\BaseRepository;
use App\Repositories\Coin\Interfaces\TagRepositoryInterface;

/**
 * Class TagRepository
 *
 * Handles data operations related to tags.
 *
 * @extends BaseRepository<Tag>
 */
class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    /**
     * TagRepository constructor.
     *
     * @param  Tag  $model  The Tag model instance.
     */
    public function __construct(Tag $model)
    {
        parent::__construct($model);
    }

    /**
     * Find a tag by its ID.
     *
     * @param  int  $id  Tag ID.
     * @return Tag|null
     */
    public function find(int $id): ?Tag
    {
        return $this->model->newQuery()->find($id);
    }

    /**
     * Find a tag by its name.
     *
     * @param  string  $name  Tag name.
     * @return Tag|null
     */
    public function findByName(string $name): ?Tag
    {
        return $this->model->newQuery()->where('name', $name)->first();
    }

    /**
     * Create a new tag.
     *
     * @param  array<string, mixed>  $data  Tag data.
     * @return Tag
     */
    public function create(array $data): Tag
    {
        return $this->model->newQuery()->create($data);
    }

    /**
     * Update a tag by ID.
     *
     * @param  int  $id  Tag ID.
     * @param  array<string, mixed>  $data  Updated data.
     * @return bool True if updated successfully.
     */
    public function update(int $id, array $data): bool
    {
        $tag = $this->find($id);

        return $tag ? $tag->update($data) : false;
    }

    /**
     * Delete a tag by ID.
     *
     * @param  int  $id  Tag ID.
     * @return bool True if deleted successfully.
     */
    public function delete(int $id): bool
    {
        $tag = $this->find($id);

        return $tag ? $tag->delete() : false;
    }
}
