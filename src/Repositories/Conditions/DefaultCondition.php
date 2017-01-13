<?php

namespace Ipunkt\LaravelJsonApi\Repositories\Conditions;

use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions;

class DefaultCondition implements RepositoryCondition
{
    /**
     * field name
     *
     * @var string
     */
    private $name;

    /**
     * field value
     *
     * @var mixed
     */
    private $value;

    /**
     * DefaultCondition constructor.
     * @param string $name
     * @param mixed $value
     */
    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * apply a builder
     *
     * @param TakesConditions $builder
     */
    function apply(TakesConditions $builder)
    {
        $builder->where($this->name, $this->value);
    }
}
