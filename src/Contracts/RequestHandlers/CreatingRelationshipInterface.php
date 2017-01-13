<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

interface CreatingRelationshipInterface extends HandlesRelationshipPostRequest
{
    /**
     * returns creating rules
     *
     * @return array
     */
    public function getCreatingRules() : array;
}