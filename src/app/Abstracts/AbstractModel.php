<?php

namespace App\Abstracts;

use App\Interfaces\ModelInterface;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    public function findById(int $id): ?ModelInterface
    {
        return parent::find($id);
    }
}
