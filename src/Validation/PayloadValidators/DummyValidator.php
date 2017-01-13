<?php

namespace Ipunkt\LaravelJsonApi\Validation\PayloadValidators;

use Tymon\JWTAuth\Validators\PayloadValidator;

/**
 * Class DummyValidator
 *
 * Überschreibt das prüfen auf gültige Timestamps in validateTimestamps durch ein durchgängiges OK.
 * -> wird benötigt um in RefreshTokenTest abgelaufen Tokens erstellen zu können.
 */
class DummyValidator extends PayloadValidator
{
    /**
     * @param array $payload
     * @return bool
     */
    protected function validateTimestamps(array $payload)
    {
        return true;
    }
}
