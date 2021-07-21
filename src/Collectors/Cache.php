<?php

namespace Bulbadev\Autodoc\Collectors;

use Bulbadev\Autodoc\Elements\Document;
use Bulbadev\Autodoc\Strategies\BuildStrategy;

class Cache implements Collector
{

    public function saveTmp(BuildStrategy $strategy): void
    {
    }

    public function saveFinal(BuildStrategy $strategy): void
    {
    }

    public function finish(BuildStrategy $strategy): void
    {
    }

    public function flush(): void
    {
    }

    public function getDocument(): Document
    {
        try {
            $store = file_get_contents('store');
            if ($store) {
                return unserialize($store);
            } else {
                return new Document();
            }
        } catch (\Throwable $e) {
            return new Document();
        }
    }

    public function getPreviousDocument(): ?array
    {
        return null;
    }
}