<?php

namespace Ipunkt\LaravelJsonApi\Services\RelationshipFilterParser;

use Illuminate\Support\Facades\Facade;

class RelationshipFilterParserFacade extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return RelationshipFilterParser::class;
    }
}