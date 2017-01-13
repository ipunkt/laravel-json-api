<?php

namespace Ipunkt\LaravelJsonApi\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RelatedRepository
{
    /**
     * returns item request
     *
     * @param Model|mixed $model
     * @param string|int $id
     * @return Model|mixed
     */
    public function findRelated($model, $id);
}