<?php

namespace Mql21\DDDMakerBundle\Locator;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;

class PathLocator
{
    private ConfigManager $configManager;
    
    public function __construct(ConfigManager $configManager)
    {
        $this->configManager = $configManager;
    }
    
    public function forBoundedContexts(): string
    {
        return $this->configManager->getBoundedContextPath();
    }
    
    public function forModules(string $boundedContextName): string
    {
        return $this->configManager->getModulePath($boundedContextName);
    }
    
    public function forBoundedContextModules(string $boundedContextName, string $moduleName): string
    {
        return $this->configManager->getModulePath($boundedContextName) . "/{$moduleName}/";
    }
    
    public function for(string $boundedContextName, string $moduleName, string $dddElement): string
    {
        return $this->configManager->pathFor($boundedContextName, $moduleName, $dddElement);
    }
}