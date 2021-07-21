<?php

namespace Bulbadev\Autodoc\TestCases;

use Bulbadev\Autodoc\Strategies\BuildStrategy;

trait AutodocTestCaseTrait
{

    public function tearDown(): void
    {
        if (config('autodoc.enabled')) {
            if (!$this->isInIsolation()) {
                $currentTestCount = $this->getTestResultObject()
                    ->count();
                $allTestCount     = $this->getTestResultObject()
                    ->topTestSuite()
                    ->count();

                if (!$this->hasFailed() && ($currentTestCount === $allTestCount)) {
                    app(BuildStrategy::class)->finish();
                }
            }
        }

        parent::tearDown();
    }
}
