<?php

namespace Bulbadev\Autodoc\Strategies;

use Illuminate\Support\Arr;

class AddNew extends BuildStrategy
{

    public function mergeForSwagger303(array $oldPaths, array $newPaths): array
    {
        foreach ($newPaths as $path => $methods)
        {
            if (isset($oldPaths[$path]))
            {
                $oldMethods      = array_keys($oldPaths[$path]);
                $newPaths[$path] = Arr::except($methods, $oldMethods);
            }
        }

        return $this->mergeWithSavingEndpoints($oldPaths, $newPaths);
    }
}