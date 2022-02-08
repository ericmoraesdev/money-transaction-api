<?php

namespace App\Abstracts;

use App\Interfaces\ModelInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model implements ModelInterface
{
    public function findById(int $id): ?ModelInterface
    {
        return parent::find($id);
    }

    /**
     * Apply condition to the query.
     *
     * @param  string  $column
     * @param  string  $operator
     * @param  mixed   $value
     *
     * @return Builder
     */
    public function where(string $column, string $operator, $value)
    {
        return parent::where($column, $operator, $value);
    }

    public function count(): int
    {
        return parent::count();
    }
}
