<?php

namespace Bulbadev\Autodoc\Commands;

use Bulbadev\Autodoc\ApiVersions\Base;
use Bulbadev\Autodoc\AutodocRunner;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AutodocCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autodoc:generate {path?}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate documentation for API';

    public function handle()
    {
        if (config('app.env') === 'production') {
            $this->error('Not used in production!');

            return;
        }

        if ($this->useDefaultConfig()) {
            return;
        }

        $apiVersions = config('autodoc.api_versions');
        if (empty($apiVersions)) {
            $this->error('You must create API version class extended by ' . Base::class);

            return;
        }

        $apiVersion = $this->choice('Select API class', $apiVersions);
        if ($apiVersion instanceof Base) {
            $this->error('API version class must be extended by ' . Base::class);
        }

        $strategy = $this->choice('Select strategy', AutodocRunner::STRATEGIES);
        $client   = $this->choice('Select client', AutodocRunner::CLIENTS);

        $phpunitPath = null;
        if ($this->confirm('Do you want add custom path for phpunit.xml?')) {
            $phpunitPath = $this->ask('Enter your custom phpunit.xml path with name and extantion');
        }

        $version = null;
        if ($this->confirm(
            'Do you want set API version build? If not, then the minor version will be increased by 1'
        ))
        {
            $version = $this->ask('Enter API version build, for example 1.0.21', '');
        }

        $this->exec($strategy, $client, $apiVersion, $version, $phpunitPath);
    }


    public function useDefaultConfig(): bool
    {
        $strategy        = AutodocRunner::STRATEGY_UPDATE_OLD;
        $client          = AutodocRunner::CLIENT_SWAGGER303;
        $apiVersionClass = Arr::first(config('autodoc.api_versions'));

        return $this->exec($strategy, $client, $apiVersionClass);
    }


    protected function exec(?string $strategy = null, ?string $client = null, ?string $apiVersionClass = null, ?string $apiVersion = null, ?string $phpunitPath = null): bool
    {
        $apiVersionInstance = app($apiVersionClass);
        $tableHeaders       = ['Setting', 'Value', 'Comment'];
        $tableValues        = [
            ['api class', Str::afterLast($apiVersionClass, '\\'), $apiVersionClass],
            ['strategy', $strategy, $strategy],
            ['client', $client, $client],
            ['phpunit.xml', $phpunitPath ?? 'default', ''],
            ['tests', $this->argument('path') ?? $apiVersionInstance->getTestsPath(), ''],
        ];
        if (!empty($apiVersion))
        {
            $tableValues[] = ['version', $apiVersion, ''];
        }

        $this->table($tableHeaders, $tableValues);

        if ($this->confirm('Is correct settings?', true))
        {
            app(AutodocRunner::class)
                ->setStrategy($strategy)
                ->setClient($client)
                ->setApiClass($apiVersionInstance)
                ->when(!empty($apiVersion), fn($builder) => $builder->setVersion($apiVersion))
                ->when($phpunitPath, fn($builder) => $builder->setPhpunitPath($phpunitPath))
                ->when($this->argument('path'), fn($builder) => $builder->setCustomTestsPath($this->argument('path')))
                ->run();

            $this->info('Check API documentation by path ' . $apiVersionInstance->getFinalPath());

            return true;
        }

        return false;
    }
}
