<?php

namespace Ipunkt\LaravelJsonApi\Repositories\Conditions;

class FlagCondition extends DefaultCondition
{
    /**
     * FlagCondition constructor.
     * @param string $fieldName
     * @param bool $fieldValue
     */
    public function __construct($fieldName, $fieldValue = true)
    {
        parent::__construct($fieldName, $fieldValue === true);
    }
}