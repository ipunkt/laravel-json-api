<?php

namespace Ipunkt\LaravelJsonApi\Testing;

use Ipunkt\LaravelJsonApi\Testing\Concerns\AssertsApiResponse;
use Ipunkt\LaravelJsonApi\Testing\Concerns\MakesApiRequests;
use Ipunkt\LaravelJsonApi\Testing\Concerns\PreparesRequestBody;

trait ApiTestCaseTrait
{
    use MakesApiRequests,
        PreparesRequestBody,
        AssertsApiResponse;
}
