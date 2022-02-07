<?php

namespace App\Interfaces;

interface ModelInterface
{
    /**
     * Find the model on database by given id
     *
     * @param array $options
     * @return ModelInterface
     */
    public function findById(int $id): ?self;

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     */
    public function save(array $options = []);

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete();
}
