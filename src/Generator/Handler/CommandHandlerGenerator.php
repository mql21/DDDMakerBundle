<?php

namespace Mql21\DDDMakerBundle\Generator\Handler;

use Mql21\DDDMakerBundle\Exception\DirectoryNotFoundException;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\ValueObject\Class\AttributeName;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassMetadata;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassName;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassNamespace;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassToHandle;
use Mql21\DDDMakerBundle\ValueObject\HandlerClass;

class CommandHandlerGenerator extends HandlerGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $handlerName): void
    {
        $dddClassBuilder = $this->classBuilder
            ->forBoundedContext($boundedContextName)
            ->forModule($moduleName)
            ->withClassName($handlerName)
            ->ofDDDElementType($this->type())
            ->build();
        
        if (file_exists($dddClassBuilder->elementFullPath())) {
            ElementAlreadyExistsException::raise($handlerName, $boundedContextName, $moduleName);
        }
        
        $useCaseNamespace = $this->configManager->namespaceFor($boundedContextName, $moduleName, 'use-case');
        $classToHandleSuffix = $this->configManager->classSuffixFor($this->handles());
        
        $classToHandleNamespace = $this->configManager->namespaceFor(
            $boundedContextName,
            $moduleName,
            $this->handles()
        );
        
        if (!file_exists(dirname($dddClassBuilder->elementFullPath()))) {
            DirectoryNotFoundException::raise($dddClassBuilder->elementFullPath());
        }
        
        $handlerClass = $this->handlerClass(
            $dddClassBuilder,
            $classToHandleNamespace,
            $handlerName,
            $classToHandleSuffix,
            $useCaseNamespace
        );
        
        file_put_contents(
            $dddClassBuilder->elementFullPath(),
            $this->renderer->render($handlerClass)
        );
    }
    
    private function handlerClass(
        DDDClassBuilder $dddClassBuilder,
        string $classToHandleNamespace,
        string $handlerName,
        ?string $classToHandleSuffix,
        string $useCaseNamespace
    ): HandlerClass {
        return new HandlerClass(
            new ClassMetadata(
                ClassNamespace::create($dddClassBuilder->namespace()),
                ClassName::create($dddClassBuilder->elementClassName())
            ),
            new ClassNamespace($dddClassBuilder->interfaceToImplementNamespace()),
            new ClassNamespace($dddClassBuilder->classToExtendNamespace()),
            new ClassToHandle(
                ClassNamespace::create("{$classToHandleNamespace}\\{$handlerName}{$classToHandleSuffix}"),
                ClassName::create("{$handlerName}{$classToHandleSuffix}"),
                AttributeName::create($this->handles())
            ),
            new ClassMetadata(
                ClassNamespace::create("{$useCaseNamespace}\\{$this->useCaseResponse->useCase()}"),
                ClassName::create($this->useCaseResponse->useCase())
            ),
            new ClassNamespace(null)
        );
    }
    
    public function type(): string
    {
        return 'command-handler';
    }
    
    public function handles(): string
    {
        return 'command';
    }
}