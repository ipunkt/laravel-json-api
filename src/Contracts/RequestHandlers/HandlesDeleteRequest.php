<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest;

interface HandlesDeleteRequest extends ApiRequestHandler
{
    /**
     * handles delete request
     *
     * @param string|int $id
     * @param \Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest $request
     * @return void
     */
    public function delete($id, ApiRequest $request);
}
