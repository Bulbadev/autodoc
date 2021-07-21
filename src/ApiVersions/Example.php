<?php

namespace Bulbadev\Autodoc\ApiVersions;

class Example extends Base
{

    public function getBaseApiPath(): string
    {
        return 'api/v2';
    }

    public function getSaveDocName(): string
    {
        return 'autodoc-v2';
    }

    public function getSaveDocPath(): string
    {
        return base_path() . '/';
    }

    public function getFinalPath(): string
    {
        return $this->getSaveDocPath() . $this->getSaveDocName() . '.' . $this->getSaveDocExt();
    }

    public function getTestsPath(): string
    {
        return 'tests/api/v2/';
    }

    public function getServers(): array
    {
        return [
            [
                'url'         => config('app.url') . 'api/v2',
                'description' => 'Base URL'
            ],
        ];
    }
}