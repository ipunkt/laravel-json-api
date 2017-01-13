<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\RelatedRepository;

interface HandlesRelationshipCollectionRequest extends ApiRequestHandler
{
    /**
     * relationship index request
     *
     * @param Model|mixed $resourceModel
     * @param RelatedRepository $repository
     * @return Collection|[]|Model|mixed
     */
    public function relatedCollection($resourceModel, RelatedRepository $repository);
}