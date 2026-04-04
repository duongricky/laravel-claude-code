<?php

namespace App\Data;

class ProductData
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?float $price = null,
        public readonly ?int $category_id = null,
        public readonly ?string $description = null,
        public readonly ?bool $is_active = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            price: isset($data['price']) ? (float) $data['price'] : null,
            category_id: $data['category_id'] ?? null,
            description: $data['description'] ?? null,
            is_active: $data['is_active'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name'        => $this->name,
            'price'       => $this->price,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'is_active'   => $this->is_active,
        ], fn ($value) => $value !== null);
    }
}
