<?php

namespace Bulbadev\Autodoc;

use Bulbadev\Autodoc\ApiVersions\Base as BaseApiVersion;

class AutodocRunner
{

    public const STRATEGY_FORCE         = 'Force';
    public const STRATEGY_ADD_NEW       = 'AddNew';
    public const STRATEGY_NEW_OR_UPDATE = 'NewOrUpdate';
    public const STRATEGY_UPDATE_OLD    = 'UpdateOld';
    public const STRATEGIES             = [
        self::STRATEGY_FORCE         => 'Rewrite from scratch',
        self::STRATEGY_ADD_NEW       => 'Only add new endpoints',
        self::STRATEGY_UPDATE_OLD    => 'Only update old endpoints',
        self::STRATEGY_NEW_OR_UPDATE => 'Update old endpoints or create new',
    ];
    public const CLIENT_SWAGGER303      = 'Swagger303';
    public const CLIENTS                = [
        self::CLIENT_SWAGGER303 => 'Swagger OPENAPI v.3.0.3',
    ];
    public BaseApiVersion $api;
    public string         $phpunitXmlPath;
    public string         $customTestsPath;
    public array          $testsEnvParameters;

    public function __construct()
    {
        $this->testsEnvParameters = [
            'AUTODOC_ENABLED'  => 'true',
            'AUTODOC_STRATEGY' => self::STRATEGY_FORCE,
            'AUTODOC_CLIENT'   => self::CLIENT_SWAGGER303,
        ];
    }

    public function getPhpunitXmlPath(): ?string
    {
        if (isset($this->phpunitXmlPath)) {
            return "-c $this->phpunitXmlPath";
        }

        return null;
    }

    public function getTestsEnvParameters(): string
    {
        $result = '';
        foreach ($this->testsEnvParameters as $key => $value) {
            $result .= "$key='$value' ";
        }

        return $result;
    }

    public function getTestsPath(): string
    {
        return $this->customTestsPath ?? $this->api->getTestsPath();
    }

    public function run()
    {
        passthru(
            $this->getTestsEnvParameters() . ' vendor/bin/phpunit' . ' ' . $this->getPhpunitXmlPath(
            ) . ' ' . $this->getTestsPath()
        );
    }

    public function setApiClass(BaseApiVersion $api): self
    {
        $this->api                = $api;
        $this->testsEnvParameters = array_merge($this->testsEnvParameters, ['AUTODOC_APICLASS' => \get_class($api)]);

        return $this;
    }

    public function setClient(string $client): self
    {
        $this->testsEnvParameters = array_merge($this->testsEnvParameters, ['AUTODOC_CLIENT' => $client]);

        return $this;
    }

    public function setVersion(string $version): self
    {
        $this->testsEnvParameters = array_merge($this->testsEnvParameters, ['AUTODOC_VERSION' => $version]);

        return $this;
    }

    public function setCustomTestsPath(string $testsPath): self
    {
        $this->customTestsPath = $testsPath;

        return $this;
    }

    public function setPhpunitPath(string $phpunitXml): self
    {
        $this->phpunitXmlPath = $phpunitXml;

        return $this;
    }

    public function setStrategy(string $strategy): self
    {
        $this->testsEnvParameters = array_merge($this->testsEnvParameters, ['AUTODOC_STRATEGY' => $strategy]);

        return $this;
    }

    public function when($bool, callable $callback): self
    {
        if ($bool) {
            $callback($this);
        }

        return $this;
    }
}