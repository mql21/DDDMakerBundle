<?php

namespace Mql21\DDDMakerBundle\Locator;

use Mql21\DDDMakerBundle\Exception\ElementNotFoundException;
use Mql21\DDDMakerBundle\Finder\Directory\BoundedContextFinder;
use Mql21\DDDMakerBundle\Finder\Directory\ModuleFinder;

class BoundedContextModuleLocator
{
    private DirectoryLocator $directoryLocator;
    private BoundedContextFinder $boundedContextFinder;
    private ModuleFinder $moduleFinder;
    
    public function __construct(
        DirectoryLocator $directoryLocator,
        BoundedContextFinder $boundedContextFinder,
        ModuleFinder $moduleFinder
    ) {
        $this->directoryLocator = $directoryLocator;
        $this->boundedContextFinder = $boundedContextFinder;
        $this->moduleFinder = $moduleFinder;
    }
    
    public function checkIfBoundedContextModuleExists(string $boundedContextName, string $moduleName): void
    {
        if (!$this->directoryLocator->boundedContextExists($boundedContextName)) {
            $this->displayBoundedContextNotFoundError($boundedContextName);
        }
        
        if (!$this->directoryLocator->moduleExists($boundedContextName, $moduleName)) {
            $this->displayModuleNotFoundError($moduleName, $boundedContextName);
        }
    }
    
    
    protected function displayBoundedContextNotFoundError(string $boundedContextName): void
    {
        ElementNotFoundException::raiseBoundedContextNotFound($boundedContextName, $this->boundedContextFinder->find());
    }
    
    protected function displayModuleNotFoundError(string $moduleName, string $boundedContextName): void
    {
        ElementNotFoundException::raiseModuleNotFound(
            $moduleName,
            $boundedContextName,
            $this->moduleFinder->findIn($boundedContextName)
        );
    }
}