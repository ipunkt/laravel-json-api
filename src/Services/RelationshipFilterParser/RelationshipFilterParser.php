<?php

namespace Ipunkt\LaravelJsonApi\Services\RelationshipFilterParser;

class RelationshipFilterParser
{
    /**
     * reduce for only filters that concern the $relationshipResource.
     * This is done through splitting by the dot and comparing the second-to-last to the relationshipResource
     * `a.b.c` => $values would compare $relationshipResource with `b` and if applicable sets `c` => $values
     *
     * @param string $resource
     * @param string $relationshipResource
     * @param array $filters
     * @return array
     */
    public function filtersForRelationship(string $resource, string $relationshipResource, array $filters)
    {

        if (!is_array($filters)) {
            return [];
        }

        $applicableFilters = array_filter($filters, function ($key) use ($resource, $relationshipResource) {
            $filter = explode('.', $key);
            $count = count($filter);
            if ($count < 2) {
                return false;
            }

            $relationNameMatches = $filter[$count - 2] === $relationshipResource;
            if ($count === 2) {
                return $relationNameMatches;
            }

            // $count > 3
            $resourceNameMatches = $filter[$count - 3] === $resource;

            return $relationNameMatches && $resourceNameMatches;
        }, ARRAY_FILTER_USE_KEY);

        $filters = [];
        foreach ($applicableFilters as $filterName => $value) {
            $filterParts = explode('.', $filterName);
            $filter = array_last($filterParts);
            $filters[$filter] = $value;
        }

        return $filters;
    }
}