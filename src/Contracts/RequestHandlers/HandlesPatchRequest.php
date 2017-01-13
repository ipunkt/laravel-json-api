<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest;
use Tobscure\JsonApi\Parameters;

interface HandlesPatchRequest extends ApiRequestHandler
{
    /**
     * handles patch request
     * returns true when no model changes appear than given in request
     * returns model for a 200 response with model
     *
     * @param string|int $id
     * @param \Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest $request
     * @param Parameters $parameters
     * @return Model|bool
     */
    public function patch($id, ApiRequest $request, Parameters $parameters);
}
