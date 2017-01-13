<?php

namespace Ipunkt\LaravelJsonApi\Contracts;

use Illuminate\Database\Eloquent\Model;

interface OneToOneRelationRepository extends RelatedRepository
{
    /**
     * returns collection request
     *
     * @param Model|mixed $model
     * @return Model|mixed
     */
    public function getOne($model);
}