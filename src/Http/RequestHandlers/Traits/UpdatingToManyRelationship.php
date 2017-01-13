<?php

namespace Ipunkt\LaravelJsonApi\Http\RequestHandlers\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest;
use Ipunkt\LaravelJsonApi\Http\Requests\RequestModel;

/**
 * This trait handles an update call to a To-Many relationship like user->tags.
 *
 * @see \Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\UpdatingToManyRelationshipInterface
 */
trait UpdatingToManyRelationship
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

        $this->validate($requestModel, $this->getUpdatingRules());

        /** @var BelongsToMany $relation */
        $relation = $this->getRelation($resourceModel);

        $data = $requestModel->map(function (RequestModel $model) use ($relation) {
            $attributes = $this->getUpdatableAttributes($relation, $model);
            if (!empty($attributes)) {
                return [$model->id() => $attributes];
            }

            return $model->id();
        });

        $relation->sync($data->all());

        return $this->getRelation($resourceModel->fresh())->get();
    }
}