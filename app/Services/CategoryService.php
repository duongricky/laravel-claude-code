<?php

namespace App\Services;

use App\Data\CategoryData;
use App\Exceptions\CategoryNotFoundException;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function __construct(
        protected readonly CategoryRepository $repository,
    ) {}

    public function getAll(): LengthAwarePaginator
    {
        return $this->repository->paginate();
    }

    public function getById(int $id): Category
    {
        $category = $this->repository->findById($id);

        if (! $category) {
            throw new CategoryNotFoundException($id);
        }

        return $category;
    }

    public function create(CategoryData $data): Category
    {
        return $this->repository->create($data->toArray());
    }

    public function update(int $id, CategoryData $data): Category
    {
        $category = $this->repository->findById($id);

        if (! $category) {
            throw new CategoryNotFoundException($id);
        }

        $this->repository->update($id, $data->toArray());

        return $category->refresh();
    }

    public function delete(int $id): void
    {
        $category = $this->repository->findById($id);

        if (! $category) {
            throw new CategoryNotFoundException($id);
        }

        $this->repository->delete($id);
    }
}
