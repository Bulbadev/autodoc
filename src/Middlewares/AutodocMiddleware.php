<?php

namespace Bulbadev\Autodoc\Middlewares;

use Bulbadev\Autodoc\Strategies\BuildStrategy as AutodocBuilderStrategy;
use Closure;

class AutodocMiddleware
{

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (config('autodoc.enabled')) {
            app(AutodocBuilderStrategy::class)->newCall($request, $response);
        }

        return $response;
    }
}
