<?php

namespace Ipunkt\LaravelJsonApi\Contracts\Exceptions;

interface ExtendedLoggingInformation
{
    /**
     * returns context for exception
     *
     * @return array
     */
    public function context();
}
