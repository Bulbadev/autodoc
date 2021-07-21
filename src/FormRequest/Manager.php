<?php

namespace Bulbadev\Autodoc\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use ReflectionMethod;

class Manager
{

    public static function buildFrom(Request $request): FormRequest
    {
        try {
            $controllerAction = explode(
                '@',
                $request->route()->getActionName()
            );
            $controller       = array_shift($controllerAction);
            $action           = array_shift($controllerAction) ?? '__invoke';
            $parameters       = (new ReflectionMethod($controller, $action))->getParameters();

            foreach ($parameters as $parameter) {
                $parameterClassName = $parameter->getType()->getName();

                if (new $parameterClassName() instanceof FormRequest) {
                    $formRequest = $parameterClassName::createFrom($request);

                    return $formRequest;
                }
            }
        } catch (\Throwable $e) {
        }

        return FormRequest::createFrom($request);
    }
}