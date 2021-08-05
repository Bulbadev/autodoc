<?php

namespace Bulbadev\Autodoc\Elements;

use Bulbadev\Autodoc\FormRequest\Manager as FormRequestManager;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Minime\Annotations\Cache\ArrayCache;
use Minime\Annotations\Interfaces\AnnotationsBagInterface;
use Minime\Annotations\Parser;
use Minime\Annotations\Reader as AnnotationReader;

class Endpoint
{
    public const TAG_DESCRIPTION      = '_description';
    public const TAG_USE_PARENT_CLASS = 'inheritDoc';

    public ParameterBag $parameterBag;
    public ResponseBag  $responseBag;
    protected Uri       $uri;
    protected Tags      $tags;
    protected Security  $security;
    protected string    $description;
    protected string    $method;

    public function __construct(Request $request, Document $document)
    {
        $formRequest         = FormRequestManager::buildFrom($request);
        $annotations         = $this->getAnnotations($formRequest);
        $this->uri           = new Uri($request, $document);
        $this->responseBag   = new ResponseBag();
        $this->parametersBag = new ParameterBag($annotations, $formRequest);
        $this->tags          = new Tags($this->uri);
        $this->description   = $annotations->get(self::TAG_DESCRIPTION, '');
        $this->method        = $request->method();
    }

    /**
     * @throws \ReflectionException
     */
    private function getAnnotations(FormRequest $formRequest, string $description = null): AnnotationsBagInterface
    {
        $annotations = (new AnnotationReader(new Parser(), new ArrayCache()))->getClassAnnotations(
            $formRequest
        );

        if ($annotations->has(self::TAG_USE_PARENT_CLASS))
        {
            $parentFormRequest = (new \ReflectionClass($formRequest))->getParentClass()
                                                                     ->getName();

            return $this->getAnnotations(new $parentFormRequest, $annotations->get(self::TAG_DESCRIPTION));
        }

        if ($description)
        {
            $annotations->set(self::TAG_DESCRIPTION, $description);
        }

        return $annotations;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->uri, $name)) {
            return $this->uri->$name($arguments);
        } elseif (method_exists($this->parameterBag, $name)) {
            return $this->parameterBag->$name($arguments);
        } elseif (method_exists($this->responseBag, $name)) {
            return $this->security->$name($arguments);
        } elseif (method_exists($this->responseBag, $name)) {
            return $this->security->$name($arguments);
        }
    }

    public function addResponse(Response $response)
    {
        $this->responseBag->addResponse($response);
    }

    public function equal(Endpoint $endpoint): bool
    {
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getName(): string
    {
        return $this->uri->getName();
    }

    public function getTags(): array
    {
        return $this->tags->getTags();
    }
}