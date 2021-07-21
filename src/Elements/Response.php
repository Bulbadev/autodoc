<?php

namespace Bulbadev\Autodoc\Elements;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

class Response
{

    protected        $example = '';
    protected string $code;
    protected string $contentType;
    protected string $description;

    public function buildFrom(HttpResponse $response): self
    {
        $this->code        = $response->getStatusCode();
        $this->contentType = $response->headers->get('content-type', '');
        $this->description = config("autodoc.code-descriptions.$this->code", '');
        $this->parseExample($response);

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getExample()
    {
        return $this->example;
    }

    protected function parseExample(HttpResponse $response)
    {
        if ($this->contentType === 'application/json') {
            $this->example = json_decode($response->content(), true);
        }
    }
}