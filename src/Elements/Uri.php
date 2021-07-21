<?php

namespace Bulbadev\Autodoc\Elements;

use Illuminate\Http\Request;

class Uri
{

    protected const SORT_METHODS = [
        'get'    => 1,
        'post'   => 2,
        'put'    => 3,
        'patch'  => 4,
        'delete' => 5,
    ];
    protected string $uriTemplate;
    protected string $uriConcrete;
    protected string $name;
    protected string $method;

    public function __construct(Request $request, Document $document)
    {
        $this->method = $request->method();
        $this->buildUriTemplate($request, $document);
        $this->buildUriConcrete($request, $document);
        $this->buildName($request);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUriConcrete(): string
    {
        return $this->uriConcrete;
    }

    public function getUriTemplate(): string
    {
        return $this->uriTemplate;
    }

    protected function buildName(Request $request): void
    {
        $this->name = $this->uriTemplate . (self::SORT_METHODS[strtolower($request->method())] ?? 9) . $request->method(
            );
    }

    protected function buildUriConcrete(Request $request, Document $document): void
    {
        $this->uriConcrete = str_replace($document->api->getBaseApiPath(), '', $request->path());
    }

    protected function buildUriTemplate(Request $request, Document $document): void
    {
        $this->uriTemplate = str_replace([$document->api->getBaseApiPath(), '?'], '', $request->route()->uri());
    }
}