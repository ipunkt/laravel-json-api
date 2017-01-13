<?php

namespace Ipunkt\LaravelJsonApi\Services\FilterApplier;

use Ipunkt\LaravelJsonApi\Contracts\FilterFactories\FilterFactory;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\ConditionAwareRepository;
use Ipunkt\LaravelJsonApi\FilterFactories\FilterFactoryNotFoundException;

class FilterApplier
{
    /**
     * Applies all filters with their respective values to the repository.
     * Filters which are not found in the factory are ignored
     *
     * @param mixed[] $filters
     * @param FilterFactory $filterFactory
     * @param ConditionAwareRepository $repository
     */
    public function applyFiltersToRepository(
        array $filters,
        FilterFactory $filterFactory,
        ConditionAwareRepository $repository
    )
    {
        if (empty($filters) && ($defaultFilter = $filterFactory->getDefaultFilter()) !== null) {
            $filters = ['default' => $defaultFilter];
        }

        foreach ($filters as $filterName => $filterValues) {
            try {
                $filter = $filterFactory->make($filterName, $filterValues);
            } catch (FilterFactoryNotFoundException $e) {
                continue;
            }

            $repository->applyCondition($filter);
        }
    }
}