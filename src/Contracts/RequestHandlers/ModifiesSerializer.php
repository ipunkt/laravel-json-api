<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Ipunkt\LaravelJsonApi\Serializers\Serializer;
use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\SerializerInterface;

interface ModifiesSerializer
{
    /**
     * modifies serializer
     *
     * @param SerializerInterface|AbstractSerializer|Serializer $serializer
     * @return SerializerInterface
     */
    public function modify(SerializerInterface $serializer);
}