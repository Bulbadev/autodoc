<?php

namespace Bulbadev\Autodoc\Elements;

class ResponseBag
{

    /** @var Response[] $responses */
    public array $responses = [];

    public function addResponse(Response $response): void
    {
        if (!isset($this->responses[$response->getCode()])) {
            $this->responses[$response->getCode()] = $response;
        }
    }
}