<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\JsonApiRepository;
use Tobscure\JsonApi\Parameters;

interface HandlesCollectionRequest extends ApiRequestHandler
{
    /**
     * index request
     *
     * @param JsonApiRepository $repository
     * @param Parameters $parameters
     * @return Model[]|\Serializable[]|Collection|array
     */
    public function index(JsonApiRepository $repository, Parameters $parameters);
}
