<?php

namespace Mql21\DDDMakerBundle\Finder\Directory;

use Mql21\DDDMakerBundle\Locator\PathLocator;

class ModuleFinder
{
    private PathLocator $pathLocator;
    
    public function __construct(PathLocator $pathLocator)
    {
        $this->pathLocator = $pathLocator;
    }
    
    public function findIn(string $boundedContextName): array
    {
        $boundedContextPath = $this->pathLocator->forModules($boundedContextName);
        $elementsInBoundedContextDirectory = scandir($boundedContextPath);
    
        return array_filter(
            $elementsInBoundedContextDirectory,
            function ($element) use ($boundedContextPath) {
                $elementFullPath = $boundedContextPath . $element;
            
                return is_dir($elementFullPath) && $element !== "." && $element !== "..";
            }
        );
    }
}