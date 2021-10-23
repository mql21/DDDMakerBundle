<?php

namespace Mql21\DDDMakerBundle\Finder\Directory;

use Mql21\DDDMakerBundle\Locator\PathLocator;

class BoundedContextFinder
{
    private PathLocator $pathLocator;
    
    public function __construct(PathLocator $pathLocator)
    {
        $this->pathLocator = $pathLocator;
    }
    
    public function find(): array
    {
        $basePath = $this->pathLocator->forBoundedContexts();
        $elementsInSrcDirectory = scandir($basePath);
    
        return array_filter(
            $elementsInSrcDirectory,
            function ($element) use ($basePath) {
                $elementFullPath = $basePath . $element;
            
                return is_dir($elementFullPath) && $element !== "." && $element !== "..";
            }
        );
    }
}