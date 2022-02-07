<?php

namespace App\Abstracts\User;

use App\Abstracts\AbstractRepository;
use App\Interfaces\User\UserModelInterface;
use App\Interfaces\User\UserEntityInterface;

abstract class AbstractUserRepository extends AbstractRepository
{

    public abstract function save(UserEntityInterface $user): UserEntityInterface;

    protected function model(): ?UserModelInterface
    {
        return null;
    }

    public function insert(UserEntityInterface $user): UserEntityInterface
    {
        return $this->save($user);
    }

    public function update(UserEntityInterface $user): UserEntityInterface
    {
        return $this->save($user);
    }

    public function delete(UserEntityInterface $user): UserEntityInterface
    {
        $userModel = $this->getModelById($user->getId());
        $userModel->delete();

        return $user;
    }
}
