<?php

namespace Ipunkt\LaravelJsonApi\Contracts;

use Illuminate\Database\Eloquent\Model;

interface OneToOneRelationRepository
{
    /**
     * returns collection request
     *
     * @param Model|mixed $model
     * @return Model|mixed
     */
    public function getOne($model);
}