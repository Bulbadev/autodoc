<?php

namespace Bulbadev\Autodoc\Strategies;

use Illuminate\Support\Arr;

class UpdateOld extends BuildStrategy
{

    public function mergeForSwagger303(array $oldPaths, array $newPaths): array
    {
        foreach ($newPaths as $endpoint => $data)
        {
            if (isset($oldPaths[$endpoint]))
            {
                $oldMethods          = array_keys($oldPaths[$endpoint]);
                $newPaths[$endpoint] = Arr::only($data, $oldMethods);
            }
        }

        return array_merge($oldPaths, $newPaths);
    }
}