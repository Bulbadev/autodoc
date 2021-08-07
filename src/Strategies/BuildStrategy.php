<?php

namespace Bulbadev\Autodoc\Strategies;

use Bulbadev\Autodoc\Collectors\Collector;
use Bulbadev\Autodoc\Elements\Document;
use Bulbadev\Autodoc\Elements\Endpoint;
use Bulbadev\Autodoc\Elements\Response as ResponseElement;
use Bulbadev\Autodoc\Elements\Uri;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

abstract class BuildStrategy
{

    public Document  $document;
    public Collector $collector;

    public function __construct()
    {
        $this->collector = app(Collector::class);
        $this->document  = $this->collector->getDocument($this);
        $this->collector->flush($this);
    }

    public function newCall(Request $request, HttpResponse $response): void
    {
        $this->addEndpoint($request, $response, $this->document);
        $this->collector->saveTmp($this);
    }

    public function finish(): void
    {
        $this->collector->finish($this);
    }

    abstract public function mergeForSwagger303(array $oldPaths, array $newPaths): array;

    protected function addEndpoint(Request $request, HttpResponse $response, Document $document): Document
    {
        $path     = new Uri($request, $document);
        $example  = (new ResponseElement())->buildFrom($response);
        $endpoint = $document->getExistingEndpoint($path) ?? new Endpoint($request, $document);
        $endpoint->addResponse($example);
        $document->addEndpoint($endpoint);

        return $document;
    }

    protected function mergeWithSavingEndpoints(array $oldPaths, array $newPaths): array
    {
        foreach ($newPaths as $path => $methods)
        {
            if (isset($oldPaths[$path]))
            {
                $oldPaths[$path] = array_merge($oldPaths[$path], $methods);
            }
            else
            {
                $oldPaths[$path] = $methods;
            }
        }

        return $oldPaths;
    }
}