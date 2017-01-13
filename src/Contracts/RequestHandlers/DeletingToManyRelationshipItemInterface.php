<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface DeletingToManyRelationshipItemInterface extends HandlesRelationshipItemDeleteRequest
{
    /**
     * returns To-Many relation for model
     *
     * @param Model $resourceModel
     * @return BelongsToMany
     */
    public function getRelation(Model $resourceModel) : BelongsToMany;
}