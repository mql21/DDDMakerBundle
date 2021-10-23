<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;

class MissingDirectoriesGenerator
{
    private const DIRECTORY_PERMISSIONS = 0775;
    private ConfigManager $configManager;
    
    public function __construct(ConfigManager $configManager)
    {
        $this->configManager = $configManager;
    }
    
    public function generate(string $boundedContextName, string $moduleName): void
    {
        $directories = $this->configManager->directoriesFor($boundedContextName, $moduleName);
    
        foreach ($directories as $directory) {
            if (!file_exists($directory)) {
                mkdir($directory, self::DIRECTORY_PERMISSIONS, true);
            }
        }
    }
}