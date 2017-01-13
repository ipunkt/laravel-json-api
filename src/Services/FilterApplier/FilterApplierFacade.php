<?php

namespace Ipunkt\LaravelJsonApi\Services\FilterApplier;

use Illuminate\Support\Facades\Facade;

class FilterApplierFacade extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return FilterApplier::class;
    }

}