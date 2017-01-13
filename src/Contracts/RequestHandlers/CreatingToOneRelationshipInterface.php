<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Ipunkt\LaravelJsonApi\Http\Requests\RequestModel;

interface CreatingToOneRelationshipInterface extends CreatingRelationshipInterface
{
    /**
     * returns relation for model
     *
     * @param Model $resourceModel
     * @return HasOne
     */
    public function getRelation(Model $resourceModel) : HasOne;

    /**
     * prepares attributes
     *
     * @param HasOne $relation
     * @param RequestModel $requestModel
     * @return Model
     */
    public function createRelationshipModel(HasOne $relation, RequestModel $requestModel);
}