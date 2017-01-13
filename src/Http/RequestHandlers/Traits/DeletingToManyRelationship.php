<?php

namespace Ipunkt\LaravelJsonApi\Http\RequestHandlers\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ipunkt\LaravelJsonApi\Contracts\RelatedRepository;

/**
 * Class DeletingToManyRelationship
 * @package Ipunkt\LaravelJsonApi\Http\RequestHandlers\Traits
 *
 * @uses DeletingToManyRelationshipItemInterface
 */
trait DeletingToManyRelationship
{
    /**
     * relationship item request
     *
     * @param Model $resourceModel
     * @param string|int $relatedId
     * @param RelatedRepository $repository
     * @return void
     */
    public function relatedItemDelete($resourceModel, $relatedId, RelatedRepository $repository)
    {
        //  perform find to invoke ModelNotFound exception
        $relationshipModel = $repository->findRelated($resourceModel, $relatedId);
        if ($relationshipModel === null) {
            throw (new ModelNotFoundException())->setModel(get_class($resourceModel));
        }

        $relation = $this->getRelation($resourceModel);

        $relation->detach($relatedId);
    }
}