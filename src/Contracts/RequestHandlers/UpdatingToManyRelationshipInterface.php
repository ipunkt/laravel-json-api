<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ipunkt\LaravelJsonApi\Http\Requests\RequestModel;

interface UpdatingToManyRelationshipInterface extends UpdatingRelationshipInterface
{
    /**
     * returns To-Many relation for model
     *
     * @param Model $resourceModel
     * @return BelongsToMany
     */
    public function getRelation(Model $resourceModel) : BelongsToMany;

    /**
     * prepares attributes
     *
     * @param BelongsToMany $relationshipModel
     * @param RequestModel $requestModel
     * @return array
     */
    public function getUpdatableAttributes(BelongsToMany $relationshipModel, RequestModel $requestModel) : array;
}