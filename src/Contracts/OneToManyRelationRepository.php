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

    /**
     * returns item request
     *
     * @param Model|mixed $model
     * @param string|int $id
     * @return Model|mixed
     */
    public function findRelated($model, $id);
}