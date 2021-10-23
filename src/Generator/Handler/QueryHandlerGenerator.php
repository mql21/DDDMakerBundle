<?php

namespace Mql21\DDDMakerBundle\Generator\Handler;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Exception\DirectoryNotFoundException;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\HandlerRenderer;
use Mql21\DDDMakerBundle\Maker\Interaction\Response\UseCaseResponse;
use Mql21\DDDMakerBundle\ValueObject\Class\AttributeName;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassMetadata;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassName;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassNamespace;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassToHandle;
use Mql21\DDDMakerBundle\ValueObject\HandlerClass;

class QueryHandlerGenerator extends HandlerGenerator implements DDDElementGenerator
{
    private string $responseClassName;
    
    public function __construct(
        UseCaseResponse $useCaseResponse,
        string $responseClassName,
        ConfigManager $configManager,
        HandlerRenderer $renderer,
        DDDClassBuilder $classBuilder
    ) {
        parent::__construct($useCaseResponse, $configManager, $renderer, $classBuilder);
        $this->responseClassName = $responseClassName;
    }
    
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
        
        $useCaseNamespace = $this
            ->configManager->namespaceFor($boundedContextName, $moduleName, "use-case");
        $responseNamespace = $this->
        configManager->namespaceFor($boundedContextName, $moduleName, "response");
        $responseSuffix = $this->configManager->classSuffixFor('response');
        $responseClassName = "{$this->responseClassName}{$responseSuffix}";
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
            $useCaseNamespace,
            "$responseNamespace\\$responseClassName"
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
        string $useCaseNamespace,
        string $responseNamespace
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
            new ClassNamespace($responseNamespace)
        );
    }
    
    public function type(): string
    {
        return 'query-handler';
    }
    
    private function handles(): string
    {
        return 'query';
    }
}