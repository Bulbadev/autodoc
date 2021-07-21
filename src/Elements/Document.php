<?php

namespace Bulbadev\Autodoc\Elements;

use Bulbadev\Autodoc\ApiVersions\Base as BaseApi;
use Bulbadev\Autodoc\Collectors\Collector;

class Document
{

    /** @var $endpoints Endpoint[] */
    public array     $endpoints = [];
    public Collector $collector;
    public BaseApi   $api;

    public function __construct()
    {
        $apiClass  = env('AUTODOC_APICLASS');
        $this->api = new $apiClass();
    }

    public function __call($name, $arguments)
    {
        return $this->api->$name($arguments);
    }

    public function addEndpoint(Endpoint $endpoint): self
    {
        $this->endpoints[$endpoint->getName()] = $endpoint;

        return $this;
    }

    public function getExistingEndpoint(Uri $uri): ?Endpoint
    {
        return $this->endpoints[$uri->getName()] ?? null;
    }
}