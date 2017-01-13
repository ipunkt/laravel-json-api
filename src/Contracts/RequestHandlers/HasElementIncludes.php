<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Tobscure\JsonApi\ElementInterface;

interface HasElementIncludes
{
    /**
     * has automatic includes
     *
     * @param ElementInterface $element
     */
    public function includes(ElementInterface $element);
}