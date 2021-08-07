<?php

namespace Bulbadev\Autodoc\Strategies;

use Illuminate\Support\Arr;

class UpdateOld extends BuildStrategy
{

    public function mergeForSwagger303(array $oldPaths, array $newPaths): array
    {
        foreach ($newPaths as $path => $methods)
        {
            if (isset($oldPaths[$path]))
            {
                $oldMethods      = array_keys($oldPaths[$path]);
                $newPaths[$path] = Arr::only($methods, $oldMethods);
            }
        }

        return array_merge($oldPaths, $newPaths);
    }
}