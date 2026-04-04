<?php

namespace App\Services;

use App\Data\ProductData;
use App\Exceptions\ProductNotFoundException;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(
        protected readonly ProductRepository $repository,
    ) {}

    public function getAll(): LengthAwarePaginator
    {
        return $this->repository->paginate();
    }

    public function getById(int $id): Product
    {
        $product = $this->repository->findById($id);

        if (! $product) {
            throw new ProductNotFoundException($id);
        }

        return $product;
    }

    public function create(ProductData $data): Product
    {
        return $this->repository->create($data->toArray());
    }

    public function update(int $id, ProductData $data): Product
    {
        $product = $this->repository->findById($id);

        if (! $product) {
            throw new ProductNotFoundException($id);
        }

        $this->repository->update($id, $data->toArray());

        return $product->refresh();
    }

    public function delete(int $id): void
    {
        $product = $this->repository->findById($id);

        if (! $product) {
            throw new ProductNotFoundException($id);
        }

        $this->repository->delete($id);
    }
}
