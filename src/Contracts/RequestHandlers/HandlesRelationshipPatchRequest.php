<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest;

interface HandlesRelationshipPatchRequest extends ApiRequestHandler
{
    /**
     * patch request on relationship
     *
     * @param ApiRequest $request
     * @param Model|mixed $resourceModel
     * @return Model|mixed|null
     */
    public function relatedPatch(ApiRequest $request, $resourceModel);
}