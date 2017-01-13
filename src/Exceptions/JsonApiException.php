<?php

namespace Ipunkt\LaravelJsonApi\Exceptions;

use Ipunkt\LaravelJsonApi\Contracts\Exceptions\ExtendedLoggingInformation;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class JsonApiException extends HttpException implements ExtendedLoggingInformation
{
    /**
     * returns context for exception
     *
     * @return array
     */
    public function context()
    {
        return [
            'exception' => get_class($this),
            'message' => $this->getMessage(),
            'stacktrace' => $this->getTrace(),
        ];
    }
}
