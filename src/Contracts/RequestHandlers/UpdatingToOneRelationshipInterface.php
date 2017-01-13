<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Http\Requests\RequestModel;

interface UpdatingToOneRelationshipInterface extends UpdatingRelationshipInterface
{
    /**
     * returns model for relationship
     *
     * @param Model $resourceModel
     * @return Model
     */
    public function getRelationshipModel(Model $resourceModel) : Model;

    /**
     * prepares attributes
     *
     * @param Model $relationshipModel
     * @param RequestModel $requestModel
     * @return Model|null
     */
    public function updateRelationshipModel(Model $relationshipModel, RequestModel $requestModel);
}