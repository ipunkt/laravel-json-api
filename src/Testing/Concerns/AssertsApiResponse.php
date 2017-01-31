<?php

namespace Ipunkt\LaravelJsonApi\Testing\Concerns;

/**
 * Class AssertsApiResponse
 * @package Ipunkt\LaravelJsonApi\Testing\Concerns
 *
 * Use with Laravel 5.3 or Laravel 5.4 BrowserKitTest
 */
trait AssertsApiResponse
{
    /**
     * assert error response with status code and message context
     *
     * @param int $statusCode
     * @param array|null $structure
     */
    protected function assertErrorResponse(int $statusCode, array $structure = null)
    {
        $this->assertResponseStatus($statusCode);
        $this->seeJsonStructure($structure ?? [
                'errors' => [
                    '*' => [
                        $statusCode => 'status',
                        'title'
                    ]
                ]
            ]);
    }

    /**
     * returns response id
     *
     * @return string
     */
    protected function getResponseId(): string
    {
        $json = $this->decodeResponseJson();

        return array_get($json, 'data.id');
    }

    /**
     * returns response type
     *
     * @return string
     */
    protected function getResponseType(): string
    {
        $json = $this->decodeResponseJson();

        return array_get($json, 'data.type');
    }
}