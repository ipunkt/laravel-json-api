<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ipunkt\LaravelJsonApi\Http\Requests\RequestModel;

interface CreatingToManyRelationshipInterface extends CreatingRelationshipInterface
{
    /**
     * returns To-Many relation for model
     *
     * @param Model $resourceModel
     * @return BelongsToMany
     */
    public function getRelation(Model $resourceModel) : BelongsToMany;

    /**
     * intercept before creating when necessary (check existance)
     *
     * @param array $ids
     * @param BelongsToMany $relation
     * @return void
     */
    public function beforeCreating(array $ids, BelongsToMany $relation);

    /**
     * prepares attributes
     *
     * @param BelongsToMany $relation
     * @param RequestModel $requestModel
     * @return void
     */
    public function createRelationshipModel(BelongsToMany $relation, RequestModel $requestModel);
}