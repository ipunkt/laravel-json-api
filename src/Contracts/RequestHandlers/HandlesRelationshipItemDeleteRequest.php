<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\RelatedRepository;

interface HandlesRelationshipItemDeleteRequest extends ApiRequestHandler
{
    /**
     * relationship item request
     *
     * @param Model|mixed $resourceModel
     * @param string|int $relatedId
     * @param RelatedRepository $repository
     * @return void
     */
    public function relatedItemDelete($resourceModel, $relatedId, RelatedRepository $repository);
}