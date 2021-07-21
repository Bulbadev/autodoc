<?php

namespace Bulbadev\Autodoc\Collectors;

use Bulbadev\Autodoc\Elements\Document;
use Bulbadev\Autodoc\Strategies\BuildStrategy;

interface Collector
{

    public function finish(BuildStrategy $strategy): void;

    public function flush(BuildStrategy $strategy): void;

    public function getDocument(): Document;

    public function saveFinal(BuildStrategy $strategy): void;

    public function saveTmp(BuildStrategy $strategy): void;

    public function getPreviousDocument(): ?array;
}