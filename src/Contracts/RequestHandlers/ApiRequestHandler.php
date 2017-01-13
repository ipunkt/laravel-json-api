<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Ipunkt\LaravelJsonApi\Contracts\FilterFactories\FilterFactory;
use Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest;

/**
 * Interface ApiRequestHandler
 *
 * Der Api Controller bietet einer Resource die Möglichkeit selbst einfluss darauf zu nehmen wie sie auf Anfragen regiert.
 * Wenn für eine Resource kein eigener ApiRequestHandler hinterlegt wird dann gibt die ControllerFactory den
 * `DefaultApiController` für die entsprechende Version zurück zurück.
 */
interface ApiRequestHandler
{
    const CONTENT_TYPE = 'application/vnd.api+json';

    /**
     * @param string[] $filters
     * @return ApiRequestHandler
     */
    function setFilters($filters);

    /**
     * @param FilterFactory $filterFactory
     * @return ApiRequestHandler
     */
    function setFilterFactory(FilterFactory $filterFactory);

    /**
     * set request
     *
     * @param ApiRequest $request
     * @return ApiRequestHandler
     */
    function setRequest(ApiRequest $request);
}
