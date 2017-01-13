<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest;
use Tobscure\JsonApi\Parameters;

interface HandlesPostRequest extends ApiRequestHandler
{
    /**
     * handles post request
     *
     * @param \Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest $request
     * @param Parameters $parameters
     * @return Model|mixed|null
     */
    public function store(ApiRequest $request, Parameters $parameters);
}
