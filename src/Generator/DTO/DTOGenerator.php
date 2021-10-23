<?php

namespace Mql21\DDDMakerBundle\Generator\DTO;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Exception\DirectoryNotFoundException;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\DTORenderer;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassAttributes;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassName;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassNamespace;
use Mql21\DDDMakerBundle\ValueObject\DTOClass;

abstract class DTOGenerator implements DDDElementGenerator
{
    protected ClassAttributes $classAttributes;
    protected ConfigManager $configManager;
    protected DTORenderer $renderer;
    private DDDClassBuilder $classBuilder;
    
    abstract public function type();
    
    public function __construct(
        ClassAttributes $classAttributes,
        ConfigManager $configManager,
        DDDClassBuilder $classBuilder
    ) {
        $this->classAttributes = $classAttributes;
        $this->configManager = $configManager;
        $this->renderer = new DTORenderer();
        $this->classBuilder = $classBuilder;
    }
    
    public function generate(string $boundedContextName, string $moduleName, string $className): void
    {
        $dddClassBuilder = $this->classBuilder
            ->forBoundedContext($boundedContextName)
            ->forModule($moduleName)
            ->withClassName($className)
            ->ofDDDElementType($this->type())
            ->build();
        
        if (file_exists($dddClassBuilder->elementFullPath())) {
            ElementAlreadyExistsException::raise($className, $boundedContextName, $moduleName);
        }
        
        if (!file_exists(dirname($dddClassBuilder->elementFullPath()))) {
            DirectoryNotFoundException::raise($dddClassBuilder->elementFullPath());
        }
        
        file_put_contents(
            $dddClassBuilder->elementFullPath(),
            $this->renderer->render($this->dtoClass($dddClassBuilder))
        );
    }
    
    private function dtoClass(DDDClassBuilder $dddClassBuilder): DTOClass
    {
        return new DTOClass(
            new ClassNamespace($dddClassBuilder->namespace()),
            new ClassName($dddClassBuilder->elementClassName()),
            new ClassNamespace($dddClassBuilder->interfaceToImplementNamespace()),
            new ClassNamespace($dddClassBuilder->classToExtendNamespace()),
            $this->classAttributes
        );
    }
}