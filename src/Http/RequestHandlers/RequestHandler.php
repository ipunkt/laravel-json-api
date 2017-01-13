<?php

namespace Ipunkt\LaravelJsonApi\Http\RequestHandlers;

use Ipunkt\LaravelJsonApi\Contracts\FilterFactories\FilterFactory;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\ConditionAwareRepository;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\JsonApiRepository;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\ApiRequestHandler;
use Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest;

abstract class RequestHandler implements ApiRequestHandler
{
    /**
     * @var string[]
     */
    protected $filters;

    /**
     * request
     *
     * @var ApiRequest
     */
    protected $request;

    /**
     * @var \Ipunkt\LaravelJsonApi\Contracts\FilterFactories\FilterFactory
     */
    protected $filterFactory;

    /**
     * set filters
     *
     * @param \string[] $filters
     * @return $this
     */
    function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * apply filters
     *
     * @param array $filters
     * @param FilterFactory $filterFactory
     * @param JsonApiRepository|ConditionAwareRepository $repository
     */
    public function applyFilters($filters, FilterFactory $filterFactory, ConditionAwareRepository $repository)
    {
        //  @see Ipunkt\LaravelJsonApi\Services\FilterApplier\FilterApplier
        \FilterApplier::applyFiltersToRepository($filters, $filterFactory, $repository);
    }

    /**
     * set filter factory
     *
     * @param \Ipunkt\LaravelJsonApi\Contracts\FilterFactories\FilterFactory $filterFactory
     * @return $this
     */
    function setFilterFactory(FilterFactory $filterFactory)
    {
        $this->filterFactory = $filterFactory;

        return $this;
    }

    /**
     * set request
     *
     * @param ApiRequest $request
     * @return $this|\Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\ApiRequestHandler
     */
    function setRequest(ApiRequest $request)
    {
        $this->request = $request;

        return $this;
    }
}
