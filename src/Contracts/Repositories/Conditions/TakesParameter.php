<?php

namespace Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions;

interface TakesParameter
{
    /**
     * sets parameter
     *
     * @param string $name
     * @param mixed $value
     * @return RepositoryCondition
     */
    function setParameter($name, $value);
}