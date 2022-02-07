<?php

namespace App\Repositories\User;

use App\Entities\User\User;
use Illuminate\Http\Response;
use App\Exceptions\RepositoryException;
use App\Interfaces\User\UserModelInterface;
use App\Interfaces\User\UserEntityInterface;
use App\Abstracts\User\AbstractUserRepository;
use App\Interfaces\User\UserRepositoryInterface;

class UserRepository extends AbstractUserRepository implements UserRepositoryInterface
{

    public function __construct(UserModelInterface $user)
    {
        $this->user = $user;
    }

    public function getById(int $id, bool $hidePassword = true): ?UserEntityInterface
    {
        $user = $this->user->find($id);

        if (!$user) {
            return null;
        }

        $entity = new User([
            'id' => $user->id,
            'type' => $user->type,
            'fullname' => $user->fullname,
            'cpf' => $user->cpf,
            'cnpj' => $user->cnpj,
            'email' => $user->email,
            'available_money' => $user->available_money
        ]);

        if (!$hidePassword) {
            $entity->setPassword($user->password);
        }

        return $entity;
    }

    public function save(UserEntityInterface $user): UserEntityInterface
    {
        $userModel = $this->user;

        if ($user->getId() > 0) {

            $userModel = $this->user->find($user->getId());

            if (!$userModel) {
                throw new RepositoryException(
                    sprintf('Usuário (#%s) não encontrado.', $user->getId()),
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        $userModel->type = $user->getType();
        $userModel->fullname = $user->getFullname();
        $userModel->cpf = $user->getCpf();
        $userModel->cnpj = $user->getCnpj();
        $userModel->email = $user->getEmail();
        $userModel->available_money = $user->getAvailableMoney();

        if ($user->getPassword()) {
            $userModel->password = $user->getPassword();
        }

        $userModel->save();

        return $user;
    }
}
