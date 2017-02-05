<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\RelatedRepository;

interface HandlesRelationshipDeleteRequest extends ApiRequestHandler
{
    /**
     * relationship item request
     *
     * @param Model|mixed $resourceModel
     * @param RelatedRepository $repository
     * @return void
     */
    public function relatedDelete($resourceModel, RelatedRepository $repository);
}
