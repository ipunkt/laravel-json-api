<?php

namespace Ipunkt\LaravelJsonApi\Http\RequestHandlers\Traits;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\RelatedRepository;

trait FetchingToManyRelationshipItem
{
    /**
     * relationship item request
     *
     * @param Model|mixed $resourceModel
     * @param string|int $relatedId
     * @param RelatedRepository $repository
     * @return Model|mixed
     */
    public function relatedItem($resourceModel, $relatedId, RelatedRepository $repository)
    {
        return $repository->findRelated($resourceModel, $relatedId);
    }
}