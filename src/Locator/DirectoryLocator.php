<?php

namespace Mql21\DDDMakerBundle\Locator;

class DirectoryLocator
{
    private PathLocator $locator;
    
    public function __construct(PathLocator $locator)
    {
        $this->locator = $locator;
    }
    
    public function boundedContextExists(string $boundedContextName): bool
    {
        return file_exists($this->locator->forModules($boundedContextName));
    }
    
    public function moduleExists(string $boundedContextName, string $moduleName): bool
    {
        return file_exists($this->locator->forBoundedContextModules($boundedContextName, $moduleName));
    }
}