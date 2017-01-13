<?php

namespace Ipunkt\LaravelJsonApi\Http\RequestHandlers\Traits;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest;

trait CreatingToOneRelationship
{
    /**
     * post request on To-One relationship
     *
     * @param ApiRequest $request
     * @param Model $resourceModel
     * @return Model
     * @throws \Exception
     */
    public function relatedPost(ApiRequest $request, $resourceModel)
    {
        $requestModel = $request->asRequestModel();

        $this->validate($requestModel, $this->getCreatingRules());

        $relation = $this->getRelation($resourceModel);

        return $this->createRelationshipModel($relation, $requestModel);
    }
}