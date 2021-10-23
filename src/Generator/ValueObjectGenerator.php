<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\DirectoryNotFoundException;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\ValueObjectRenderer;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassMetadata;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassName;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassNamespace;
use Mql21\DDDMakerBundle\ValueObject\ValueObject;

class ValueObjectGenerator implements DDDElementGenerator
{
    private ValueObjectRenderer $renderer;
    private DDDClassBuilder $classBuilder;
    
    public function __construct(ValueObjectRenderer $renderer, DDDClassBuilder $classBuilder)
    {
        $this->renderer = $renderer;
        $this->classBuilder = $classBuilder;
    }
    
    public function generate(string $boundedContextName, string $moduleName, string $valueObjectName): void
    {
        $dddClassBuilder = $this->classBuilder
            ->forBoundedContext($boundedContextName)
            ->forModule($moduleName)
            ->withClassName($valueObjectName)
            ->ofDDDElementType($this->type())
            ->build();
    
        if (file_exists($dddClassBuilder->elementFullPath())) {
            ElementAlreadyExistsException::raise($valueObjectName, $boundedContextName, $moduleName);
        }
    
        if (!file_exists(dirname($dddClassBuilder->elementFullPath()))) {
            DirectoryNotFoundException::raise($dddClassBuilder->elementFullPath());
        }
        
        file_put_contents(
            $dddClassBuilder->elementFullPath(),
            $this->renderer->render($this->valueObject($dddClassBuilder, $valueObjectName))
        );
    }
    
    public function type(): string
    {
        return 'value-object';
    }
    
    private function valueObject(DDDClassBuilder $dddClassBuilder, string $valueObjectName): ValueObject
    {
        return ValueObject::create(
            new ClassMetadata(
                ClassNamespace::create($dddClassBuilder->namespace()),
                ClassName::create($valueObjectName)
            )
        );
    }
}