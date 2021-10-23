<?php

declare(strict_types=1);

namespace Mql21\DDDMakerBundle\Generator\Builder;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Locator\PathLocator;

class DDDClassBuilder
{
    private PathLocator $pathLocator;
    private ConfigManager $configManager;
    
    private string $elementClassName;
    private string $elementFullPath;
    private string $namespace;
    private ?string $interfaceToImplementNamespace;
    private ?string $interfaceToImplementName;
    private ?string $classToExtendNamespace;
    private ?string $classToExtendName;
    private string $boundedContextName;
    private string $moduleName;
    private string $className;
    private string $dddElementType;
    private mixed $classSuffix;
    
    public function __construct(PathLocator $pathLocator, ConfigManager $configManager)
    {
        $this->pathLocator = $pathLocator;
        $this->configManager = $configManager;
    }
    
    public function forBoundedContext(string $boundedContextName): self
    {
        $this->boundedContextName = $boundedContextName;
        
        return $this;
    }
    
    public function forModule(string $moduleName): self
    {
        $this->moduleName = $moduleName;
        
        return $this;
    }
    
    public function withClassName(string $className): self
    {
        $this->className = $className;
        
        return $this;
    }
    
    public function ofDDDElementType(string $dddElementType): self
    {
        $this->dddElementType = $dddElementType;
        
        return $this;
    }
    
    public function build(): self
    {
        $this->checkIfClassCanBeBuilt();
        $this->buildClassSuffix();
        $this->buildElementClassName();
        $this->buildNamespace();
        $this->buildClassToImplement();
        $this->buildClassToExtend();
        $this->buildElementFullPath();
        
        return $this;
    }
    
    public function elementClassName(): string
    {
        return $this->elementClassName;
    }
    
    public function namespace(): string
    {
        return $this->namespace;
    }
    
    public function interfaceToImplementNamespace(): ?string
    {
        return $this->interfaceToImplementNamespace;
    }
    
    public function interfaceToImplementName(): ?string
    {
        return $this->interfaceToImplementName;
    }
    
    public function classToExtendNamespace(): ?string
    {
        return $this->classToExtendNamespace;
    }
    
    public function classToExtendName(): ?string
    {
        return $this->classToExtendName;
    }
    
    public function elementFullPath(): string
    {
        return $this->elementFullPath;
    }
    
    private function classToImplementReflector(string $dddElementType): ?\ReflectionClass
    {
        $classToImplement = $this->configManager->classToImplementFor($dddElementType);
        if (empty($classToImplement)) {
            return null;
        }
        
        return new \ReflectionClass($classToImplement);
    }
    
    private function classToExtendReflector(string $dddElementType): ?\ReflectionClass
    {
        $classToExtend = $this->configManager->classToExtendFor($dddElementType);
        if (empty($classToExtend)) {
            return null;
        }
        
        return new \ReflectionClass($classToExtend);
    }
    
    private function checkIfClassCanBeBuilt(): void
    {
        $this->checkIfBoundedContextNameIsMissing();
        $this->checkIfModuleNameIsMissing();
        $this->checkIfClassNameIsMissing();
        $this->checkIfDDDElementTypeIsMissing();
    }
    
    private function checkIfBoundedContextNameIsMissing(): void
    {
        if (empty($this->boundedContextName)) {
            throw new \Exception(
                "Bounded context name is required. Please use method 'forBoundedContext()' before calling build method."
            );
        }
    }
    
    private function checkIfModuleNameIsMissing(): void
    {
        if (empty($this->moduleName)) {
            throw new \Exception(
                "Module name is required. Please use method 'forModule()' before calling build method."
            );
        }
    }
    
    private function checkIfClassNameIsMissing(): void
    {
        if (empty($this->className)) {
            throw new \Exception(
                "Class name is required. Please use method 'withClassName()' before calling build method."
            );
        }
    }
    
    private function checkIfDDDElementTypeIsMissing(): void
    {
        if (empty($this->dddElementType)) {
            throw new \Exception(
                "DDD element type is required. Please use method 'ofDDDElementType()' before calling build method."
            );
        }
    }
    
    private function buildElementFullPath(): void
    {
        $elementFileName = "{$this->elementClassName}.php";
        $elementPath = $this->pathLocator->for($this->boundedContextName, $this->moduleName, $this->dddElementType);
        $this->elementFullPath = "{$elementPath}{$elementFileName}";
    }
    
    private function buildClassToExtend(): void
    {
        $classToExtendReflector = $this->classToExtendReflector($this->dddElementType);
        $this->classToExtendNamespace = $classToExtendReflector ? $classToExtendReflector->getName() : null;
        $this->classToExtendName = $classToExtendReflector ? $classToExtendReflector->getShortName() : null;
    }
    
    private function buildClassToImplement(): void
    {
        $classToImplementReflector = $this->classToImplementReflector($this->dddElementType);
        $this->interfaceToImplementNamespace = $classToImplementReflector
            ? $classToImplementReflector->getName()
            : null;
        $this->interfaceToImplementName = $classToImplementReflector
            ? $classToImplementReflector->getShortName()
            : null;
    }
    
    private function buildNamespace(): void
    {
        $this->namespace = $this->configManager->namespaceFor(
            $this->boundedContextName,
            $this->moduleName,
            $this->dddElementType
        );
    }
    
    protected function buildElementClassName(): void
    {
        $this->elementClassName = "{$this->className}{$this->classSuffix}";
    }
    
    private function buildClassSuffix(): mixed
    {
        return $this->classSuffix = $this->configManager->classSuffixFor($this->dddElementType);
    }
}