<?php

namespace Bulbadev\Autodoc\Elements;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Minime\Annotations\Interfaces\AnnotationsBagInterface as AnnotationsBag;

class ParameterBag
{

    /** @var Parameter[] $parameters */
    public array $parameters = [];

    public function __construct(AnnotationsBag $annotationsBag, FormRequest $request)
    {
        $this->parsePath($annotationsBag, $request);
        $this->parseFormRequest($annotationsBag, $request);
    }

    protected function parseFormRequest(AnnotationsBag $annotationsBag, FormRequest $request): void
    {
        $allRules = method_exists($request, 'rules') ? $request->rules() : [];
        foreach ($allRules as $name => $rules) {
            $parameter                               = (new Parameter($name))->buildFrom(
                $annotationsBag,
                $rules
            )->setIn(
                Parameter::IN_QUERY
            );
            $this->parameters[$parameter->getName()] = $parameter;
        }
    }

    protected function parsePath(AnnotationsBag $annotationsBag, FormRequest $request): void
    {
        $parameters = $request->route()->originalParameters();

        foreach ($parameters as $name => $value) {
            $isRequired                              = Str::contains($request->route()->uri(), '{' . $name . '}');
            $parameter                               = (new Parameter($name, $value))
                ->setIn(Parameter::IN_PATH)
                ->setRequiredIf($isRequired)
                ->setDescription($annotationsBag->get($name, ''));
            $this->parameters[$parameter->getName()] = $parameter;
        }
    }
}