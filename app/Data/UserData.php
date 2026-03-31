<?php

namespace App\Data;

class UserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $phone = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
