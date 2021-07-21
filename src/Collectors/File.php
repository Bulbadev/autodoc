<?php

namespace Bulbadev\Autodoc\Collectors;

use Bulbadev\Autodoc\Elements\Document;
use Bulbadev\Autodoc\Strategies\BuildStrategy;

class File implements Collector
{

    private string $tmpPath;
    private string $finalPath;

    public function saveTmp(BuildStrategy $strategy): void
    {
        file_put_contents(
            $this->getTmpPath($strategy),
            serialize($strategy->document)
        );
    }

    public function saveFinal(BuildStrategy $strategy): void
    {
        $client = 'Bulbadev\Autodoc\Clients\\' . env('AUTODOC_CLIENT', 'Swagger303');
        file_put_contents(
            $this->getFinalPath(),
            app($client)->create($strategy)
        );
    }

    public function finish(BuildStrategy $strategy): void
    {
        $this->flush($strategy);
        $this->saveFinal($strategy);
    }

    public function flush(BuildStrategy $strategy): void
    {
        @unlink($this->getTmpPath($strategy));
    }

    public function getDocument(): Document
    {
        try {
            $store = file_get_contents($this->getTmpPath());

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
        try {
            return json_decode(file_get_contents($this->getFinalPath()), true);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function getFinalPath(): string
    {
        if (isset($this->finalPath)) {
            return $this->finalPath;
        }
        $apiClass = env('AUTODOC_APICLASS');
        $api      = new $apiClass();

        return $this->finalPath = $api->getFinalPath();
    }

    private function getTmpPath(): string
    {
        if (isset($this->tmpPath)) {
            return $this->tmpPath;
        }
        $apiClass = env('AUTODOC_APICLASS');
        $api      = new $apiClass();

        return $this->tmpPath = $api->getSaveDocPath() . $api->getSaveTmpDocName();
    }
}