<?php

namespace Bulbadev\Autodoc\Commands;

use Bulbadev\Autodoc\ApiVersions\Base;
use Bulbadev\Autodoc\AutodocRunner;
use Illuminate\Console\Command;
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

        if ($this->confirm(
            'Do you want set API version build? If not, then the minor version will be increased by 1'
        )) {
            $version = $this->ask('Enter API version build, for example 1.0.21', '');
        }

        $apiClass     = app($apiVersion);
        $tableHeaders = ['Setting', 'Value', 'Comment'];
        $tableValues  = [
            ['api class', Str::afterLast($apiVersion, '\\'), $apiVersion],
            ['strategy', $strategy, AutodocRunner::STRATEGIES[$strategy]],
            ['client', $client, AutodocRunner::CLIENTS[$client]],
            ['phpunit.xml', $phpunitPath ?? 'default', ''],
            ['tests', $this->argument('path') ?? $apiClass->getTestsPath(), ''],
        ];

        if (!empty($version)) {
            $tableValues[] = ['version', $version, ''];
        }

        $this->table($tableHeaders, $tableValues);

        if (!$this->confirm('Is correct settings?')) {
            return;
        }

        app(AutodocRunner::class)
            ->setStrategy($strategy)
            ->setClient($client)
            ->setApiClass($apiClass)
            ->when(!empty($version), fn($builder) => $builder->setVersion($version))
            ->when($phpunitPath, fn($builder) => $builder->setPhpunitPath($phpunitPath))
            ->when($this->argument('path'), fn($builder) => $builder->setCustomTestsPath($this->argument('path')))
            ->run();

        $this->info('Check API documentation by path ' . $apiClass->getFinalPath());
    }
}
