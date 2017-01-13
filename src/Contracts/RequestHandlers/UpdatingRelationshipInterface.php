<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Http\Requests\RequestModel;

interface UpdatingRelationshipInterface extends HandlesRelationshipPatchRequest
{
    /**
     * returns updating rules
     *
     * @return array
     */
    public function getUpdatingRules() : array;
}