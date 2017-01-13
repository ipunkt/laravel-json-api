<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\RelatedRepository;

interface HandlesRelationshipItemRequest extends ApiRequestHandler
{
    /**
     * relationship item request
     *
     * @param Model|mixed $resourceModel
     * @param string|int $relatedId
     * @param RelatedRepository $repository
     * @return Model|mixed
     */
    public function relatedItem($resourceModel, $relatedId, RelatedRepository $repository);
}