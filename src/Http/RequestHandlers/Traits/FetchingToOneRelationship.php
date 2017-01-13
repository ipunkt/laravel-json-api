<?php

namespace Ipunkt\LaravelJsonApi\Http\RequestHandlers\Traits;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\OneToOneRelationRepository;
use Ipunkt\LaravelJsonApi\Contracts\RelatedRepository;

trait FetchingToOneRelationship
{
    /**
     * index request
     *
     * @param Model|mixed $resourceModel
     * @param OneToOneRelationRepository|RelatedRepository $repository
     * @return Model|mixed
     */
    public function relatedCollection($resourceModel, RelatedRepository $repository)
    {
        return $repository->getOne($resourceModel);
    }
}