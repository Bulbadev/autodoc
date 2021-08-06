<?php

namespace Bulbadev\Autodoc\Strategies;

use Illuminate\Support\Arr;

class AddNew extends BuildStrategy
{

    public function mergeForSwagger303(array $oldPaths, array $newPaths): array
    {
        foreach ($newPaths as $endpoint => $data)
        {
            if (isset($oldPaths[$endpoint]))
            {
                $oldMethods          = array_keys($oldPaths[$endpoint]);
                $newPaths[$endpoint] = Arr::except($data, $oldMethods);
            }
        }

        return $this->mergeWithSavingEndpoints($oldPaths, $newPaths);
    }
}