<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\DirectoryNotFoundException;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\UseCaseRenderer;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassMetadata;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassName;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassNamespace;
use Mql21\DDDMakerBundle\ValueObject\UseCase;

class UseCaseGenerator implements DDDElementGenerator
{
    private UseCaseRenderer $renderer;
    private DDDClassBuilder $classBuilder;
    
    public function __construct(UseCaseRenderer $renderer, DDDClassBuilder $classBuilder)
    {
        $this->renderer = $renderer;
        $this->classBuilder = $classBuilder;
    }
    
    public function generate(string $boundedContextName, string $moduleName, string $useCaseName): void
    {
        $dddClassBuilder = $this->classBuilder
            ->forBoundedContext($boundedContextName)
            ->forModule($moduleName)
            ->withClassName($useCaseName)
            ->ofDDDElementType($this->type())
            ->build();
        
        if (file_exists($dddClassBuilder->elementFullPath())) {
            ElementAlreadyExistsException::raise($useCaseName, $boundedContextName, $moduleName);
        }
        
        if (!file_exists(dirname($dddClassBuilder->elementFullPath()))) {
            DirectoryNotFoundException::raise($dddClassBuilder->elementFullPath());
        }
        
        file_put_contents(
            $dddClassBuilder->elementFullPath(),
            $this->renderer->render(
                $this->useCase($dddClassBuilder, $useCaseName)
            )
        );
    }
    
    public function type(): string
    {
        return 'use-case';
    }
    
    private function useCase(DDDClassBuilder $dddClassBuilder, string $useCaseName): UseCase
    {
        return UseCase::create(
            new ClassMetadata(
                ClassNamespace::create($dddClassBuilder->namespace()),
                ClassName::create($useCaseName)
            )
        );
    }
}