<?php

namespace Bulbadev\Autodoc\TestCases;

use Bulbadev\Autodoc\Strategies\BuildStrategy;
use Exception;
use PHPUnit\TextUI\DefaultResultPrinter;
use ReflectionProperty;

trait AutodocTestCaseTrait
{

    public function tearDown(): void
    {
        if (config('autodoc.enabled'))
        {
            if ($this->isInIsolation()){
                throw new Exception('You must run tests without isolation mode for generate autodoc!');
            }
            $result     = $this->getTestResultObject();
            $reflection = new ReflectionProperty($result, 'listeners');
            $reflection->setAccessible(true);
            $listeners = $reflection->getValue($result);
            foreach ($listeners as $listener)
            {
                if ($listener instanceof DefaultResultPrinter)
                {
                    $allTestCount = new ReflectionProperty($listener, 'numTests');
                    $allTestCount->setAccessible(true);
                    $allTestCount = $allTestCount->getValue($listener);
                }
            }
        }
        $currentTestCount = $this->getTestResultObject()
                                 ->count();

        if (!$this->hasFailed() && ($currentTestCount === $allTestCount))
        {
            app(BuildStrategy::class)->finish();
        }

        parent::tearDown();
    }
}
