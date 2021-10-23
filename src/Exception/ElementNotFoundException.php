<?php

namespace Mql21\DDDMakerBundle\Exception;

use Throwable;

class ElementNotFoundException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    
    public static function raiseBoundedContextNotFound(string $boundedContextName, array $availableBoundedContexts): void
    {
        $availableBoundedContexts = implode(', ', $availableBoundedContexts);
    
        throw new self("Bounded context {$boundedContextName} does not exist. Available bounded contexts: {$availableBoundedContexts}");
    }
    
    public static function raiseModuleNotFound(string $moduleName, string $boundedContextName, array $availableModules): void
    {
        $availableModules = implode(', ', $availableModules);
        
        throw new self("Module {$moduleName} does not exist in bounded context {$boundedContextName}. Available modules: {$availableModules}");
    }
}