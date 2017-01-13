<?php

namespace Ipunkt\LaravelJsonApi\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface OneToManyRelationRepository extends RelatedRepository
{
    /**
     * returns collection request
     *
     * @param Model|mixed $model
     * @return Model[]|Collection|mixed
     */
    public function getMany($model);
}