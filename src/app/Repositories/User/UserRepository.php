<?php

namespace App\Repositories\User;

use Illuminate\Http\Response;
use App\Exceptions\RepositoryException;
use App\Interfaces\User\UserModelInterface;
use App\Interfaces\User\UserEntityInterface;
use App\Abstracts\User\AbstractUserRepository;
use App\Interfaces\User\UserRepositoryInterface;

class UserRepository extends AbstractUserRepository implements UserRepositoryInterface
{
    protected $user;
    protected $userEntity;

    public function __construct(
        UserModelInterface $user,
        UserEntityInterface $userEntity
    ) {
        $this->user = $user;
        $this->userEntity = $userEntity;
    }

    public function getById(int $id, bool $hidePassword = true): ?UserEntityInterface
    {
        $user = $this->user->findById($id);

        if (!$user) {
            return null;
        }

        $userEntity = $this->userEntity->getNewInstance();

        $userEntity->populate([
            'id' => $user->id,
            'type' => $user->type,
            'fullname' => $user->fullname,
            'cpf' => $user->cpf,
            'cnpj' => $user->cnpj,
            'email' => $user->email,
            'available_money' => floatval($user->available_money)
        ]);

        if (!$hidePassword) {
            $userEntity->setPassword($user->password);
        }

        return $userEntity;
    }

    public function save(UserEntityInterface $user): UserEntityInterface
    {
        $userModel = $this->user;

        if ($user->getId() > 0) {

            $userModel = $this->user->findById($user->getId());

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
