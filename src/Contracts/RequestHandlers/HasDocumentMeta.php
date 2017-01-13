<?php

namespace Ipunkt\LaravelJsonApi\Contracts\RequestHandlers;

use Tobscure\JsonApi\Document;

interface HasDocumentMeta
{
    /**
     * has document meta
     *
     * @param Document $document
     */
    public function meta(Document $document);
}