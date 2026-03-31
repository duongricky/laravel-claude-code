<?php

namespace App\Services;

use App\Data\UserData;
use App\Events\UserCreated;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected readonly UserRepository $repository,
    ) {}

    public function create(UserData $data): User
    {
        $user = $this->repository->create([
            ...$data->toArray(),
            'password' => Hash::make('password'),
        ]);

        event(new UserCreated($user));

        return $user;
    }

    public function update(int $id, UserData $data): User
    {
        $user = $this->repository->findById($id);

        if (! $user) {
            throw new UserNotFoundException($id);
        }

        $this->repository->update($id, $data->toArray());

        return $user->refresh();
    }

    public function delete(int $id): void
    {
        $user = $this->repository->findById($id);

        if (! $user) {
            throw new UserNotFoundException($id);
        }

        $this->repository->delete($id);
    }
}
