<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\JsonApiRepository;
use Tobscure\JsonApi\Parameters;

interface HandlesItemRequest extends ApiRequestHandler
{
    /**
     * @param int|string $id
     * @param JsonApiRepository $repository
     * @param Parameters $parameters
     * @return Model|mixed
     */
    public function handle($id, JsonApiRepository $repository, Parameters $parameters);
}
