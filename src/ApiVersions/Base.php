<?php

namespace Bulbadev\Autodoc\ApiVersions;

abstract class Base
{

    abstract public function getBaseApiPath(): string;

    abstract public function getSaveDocName(): string;

    abstract public function getSaveDocPath(): string;

    abstract public function getFinalPath(): string;

    abstract public function getTestsPath(): string;

    public function getContactEmail(): ?string
    {
        return null;
    }

    public function getContactName(): ?string
    {
        return null;
    }

    public function getContactUrl(): ?string
    {
        return null;
    }

    public function getTermsOfService(): ?string
    {
        return null;
    }

    public function getLicenseName(): ?string
    {
        return null;
    }

    public function getLicenseUrl(): ?string
    {
        return null;
    }

    public function getInfoDescription(): ?string
    {
        return null;
    }

    public function getInfoTitle(): string
    {
        return '';
    }

    public function getAppUrl(): string
    {
        return str_replace(['http://', 'https://', '/'], '', config('app.url'));
    }

    public function getOpenapi(): string
    {
        return '3.0.3';
    }

    public function getSaveDocExt(): string
    {
        return config('autodoc.file_ext', 'json');
    }

    public function getSaveTmpDocName(): string
    {
        return $this->getSaveDocName() . '-tmp';
    }

    /**
     * Increment minor version from old document or set 1.0.0
     */
    public function getVersion(): string
    {
        try {
            if ($version = env('AUTODOC_VERSION', '')) {
                return $version;
            }
            $oldDocument = json_decode(
                file_get_contents($this->getSaveDocPath() . $this->getSaveDocName() . '.' . $this->getSaveDocExt()),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            $oldVersion                         = $oldDocument['info']['version'];
            $versionArr                         = explode('.', $oldVersion);
            $lastIndex                          = $versionArr[count($versionArr) - 1];
            $newVersion                         = $lastIndex + 1;
            $versionArr[count($versionArr) - 1] = $newVersion;

            return implode('.', $versionArr);
        } catch (\Throwable $e) {
            return '1.0.0';
        }
    }

    /**
     * Must be array arrays with keys 'uri' and 'description'
     * For example return [['uri' => 'site.com', 'description => 'api v.2']]
     */
    public function getServers(): array
    {
        return [];
    }
}