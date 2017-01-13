<?php

namespace Ipunkt\LaravelJsonApi\Resources;

class ResourceNotDefinedException extends \InvalidArgumentException
{
    /**
     * returns exception
     *
     * @param string $resource
     * @param string $type
     * @return ResourceNotDefinedException
     */
    public static function resource(string $resource, string $type)
    {
        return new self("Resource $resource has no definition for $type");
    }
}