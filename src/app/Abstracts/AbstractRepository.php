<?php

namespace App\Abstracts;

use App\Exceptions\RepositoryException;
use App\Interfaces\ModelInterface;

abstract class AbstractRepository
{
    protected abstract function model(): ?ModelInterface;

    protected function getModelById(int $id): ?ModelInterface
    {
        $model = $this->model()->findById($id);

        if (!$model) {
            throw new RepositoryException(sprintf('Registro (#%s) não encontrado', $id));
        }

        return $model;
    }

    public function deleteById(int $id): void
    {
        $transactionModel = $this->getModelById($id);
        $transactionModel->delete();
    }
}
