<?php

namespace Bulbadev\Autodoc\Strategies;

use Illuminate\Support\Arr;

class AddNew extends BuildStrategy
{

    public function mergeForSwagger303(array $oldPaths, array $newPaths): array
    {
        $oldPathsKeys  = array_keys($oldPaths);
        $newAddedPaths = Arr::except($newPaths, $oldPathsKeys);

        return array_merge($oldPaths, $newAddedPaths);
    }
}