<?php

namespace Ipunkt\LaravelJsonApi\Http\RequestHandlers\Traits;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest;

/**
 * This trait handles an update call to a To-One relationship like user->profile.
 *
 * @see \Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\UpdatingToOneRelationshipInterface
 */
trait UpdatingToOneRelationship
{
    /**
     * patch request on relationship
     *
     * @param ApiRequest $request
     * @param Model|mixed $resourceModel
     * @return Model|mixed
     * @throws \Exception when update failes
     */
    public function relatedPatch(ApiRequest $request, $resourceModel)
    {
        $requestModel = $request->asRequestModel();

        $relationshipModel = $this->getRelationshipModel($resourceModel);

        $this->validate($requestModel, $this->getUpdatingRules());

        return $this->updateRelationshipModel($relationshipModel, $requestModel);
    }
}