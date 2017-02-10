<?php

namespace Ipunkt\LaravelJsonApi\Repositories\Conditions;

use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions;

class OffsetCondition implements RepositoryCondition
{
    /**
     * @var int
     */
    private $offset;

    /**
     * OffsetCondition constructor.
     * @param string $offset
     */
    public function __construct($offset)
    {
        $this->offset = $offset;
    }

    /**
     * sets parameter
     *
     * @param string $name
     * @param mixed $value
     * @return RepositoryCondition
     */
    function setParameter($name, $value)
    {
        $this->offset = $value;

        return $this;
    }

    /**
     * apply a builder
     *
     * @param \Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions $builder
     */
    function apply(TakesConditions $builder)
    {
        $builder->offset($this->offset);
    }
}
