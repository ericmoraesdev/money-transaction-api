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

    /**
     * Apply condition to the query.
     *
     * @param  string  $column
     * @param  string  $operator
     * @param  mixed   $value
     *
     * @return Builder
     */
    public function where(string $column, string $operator, $value);

    /**
     * Gets the number of records in the database.
     *
     * @return integer
     */
    public function count(): int;

}
