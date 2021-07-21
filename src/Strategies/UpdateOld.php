<?php

namespace Bulbadev\Autodoc\Strategies;

use Illuminate\Support\Arr;

class UpdateOld extends BuildStrategy
{

    public function mergeForSwagger303(array $oldPaths, array $newPaths): array
    {
        $oldPathsKeys    = array_keys($oldPaths);
        $newUpdatedPaths = Arr::only($newPaths, $oldPathsKeys);

        return array_merge($oldPaths, $newUpdatedPaths);
    }
}