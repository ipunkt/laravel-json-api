<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest;

interface HandlesRelationshipPostRequest extends ApiRequestHandler
{
    /**
     * post request on relationship
     *
     * @param ApiRequest $request
     * @param Model|mixed $resourceModel
     * @return Model|mixed|null
     */
    public function relatedPost(ApiRequest $request, $resourceModel);
}