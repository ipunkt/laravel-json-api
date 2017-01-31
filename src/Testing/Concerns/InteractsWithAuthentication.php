<?php

namespace Ipunkt\LaravelJsonApi\Testing\Concerns;

trait InteractsWithAuthentication
{
    /**
     * authentication token
     *
     * @var string|null
     */
    protected static $token;

    /**
     * sets token
     *
     * @param string|null $token
     * @return $this
     */
    protected function setToken(string $token = null)
    {
        static::$token = $token;

        return $this;
    }

    /**
     * logout the user
     *
     * @return $this
     */
    protected function logout()
    {
        $this->setToken(null);

        return $this;
    }
}