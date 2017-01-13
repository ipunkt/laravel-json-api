<?php

namespace Ipunkt\LaravelJsonApi\Http\RequestHandlers\Traits;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\OneToManyRelationRepository;
use Ipunkt\LaravelJsonApi\Contracts\RelatedRepository;

/**
 * Class FetchingToManyRelationship
 * @package Ipunkt\LaravelJsonApi\Http\RequestHandlers\Traits
 *
 * @uses HandlesRelationshipCollectionRequest
 */
trait FetchingToManyRelationship
{
    /**
     * index request
     *
     * @param Model|mixed $resourceModel
     * @param OneToManyRelationRepository|RelatedRepository $repository
     * @return Model|mixed
     */
    public function relatedCollection($resourceModel, RelatedRepository $repository)
    {
        return $repository->getMany($resourceModel);
    }
}