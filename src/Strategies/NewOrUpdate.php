<?php

namespace Bulbadev\Autodoc\Strategies;

class NewOrUpdate extends BuildStrategy
{

    public function mergeForSwagger303(array $oldPaths, array $newPaths): array
    {
        return array_merge($oldPaths, $newPaths);
    }
}