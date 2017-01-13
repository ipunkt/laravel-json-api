<?php

namespace Ipunkt\LaravelJsonApi\Http\RequestHandlers\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest;
use Ipunkt\LaravelJsonApi\Http\Requests\RequestModel;

/**
 * Class CreatingToManyRelationship
 * @package Ipunkt\LaravelJsonApi\Http\RequestHandlers\Traits
 *
 * @uses CreatingToManyRelationshipInterface
 */
trait CreatingToManyRelationship
{
    /**
     * post request on To-Many relationship
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

        $ids = (array)$requestModel->id();

        /** @var BelongsToMany $relation */
        $relation = $this->getRelation($resourceModel);
        $this->beforeCreating($ids, $relation);

        $requestModel->map(function (RequestModel $model) use ($relation) {
            $this->createRelationshipModel($relation, $model);
        });

        return $this->getRelation($resourceModel->fresh())->get();
    }
}