<?php

namespace Bulbadev\Autodoc\Strategies;

class Force extends BuildStrategy
{

    public function mergeForSwagger303(array $oldPaths = null, array $newPaths): array
    {
        return $newPaths;
    }
}