<?php

namespace App\Interfaces\User;

use App\Interfaces\User\UserEntityInterface;

interface UserRepositoryInterface
{
    public function getById(int $id): ?UserEntityInterface;
    public function save(UserEntityInterface $entity): UserEntityInterface;
    public function insert(UserEntityInterface $entity): UserEntityInterface;
    public function update(UserEntityInterface $entity): UserEntityInterface;
    public function delete(UserEntityInterface $entity): UserEntityInterface;
    public function deleteById(int $id): void;
}
