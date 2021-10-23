<?php

namespace Mql21\DDDMakerBundle\ValueObject;

use Mql21\DDDMakerBundle\ValueObject\Class\AttributeName;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassMetadata;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassName;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassNamespace;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassToHandle;

class HandlerClass extends DDDElement
{
    private ClassMetadata $classMetadata;
    private ClassNamespace $interfaceNamespace;
    private ClassNamespace $parentClassNamespace;
    private ClassToHandle $classToHandle;
    private ClassMetadata $useCaseData;
    private ClassNamespace $responseClassNamespace;
    
    public function __construct(
        ClassMetadata $classMetadata,
        ClassNamespace $interfaceNamespace,
        ClassNamespace $parentClassNamespace,
        ClassToHandle $classToHandle,
        ClassMetadata $useCaseData,
        ClassNamespace $responseClassNamespace
    ) {
        $this->classMetadata = $classMetadata;
        $this->interfaceNamespace = $interfaceNamespace;
        $this->parentClassNamespace = $parentClassNamespace;
        $this->classToHandle = $classToHandle;
        $this->useCaseData = $useCaseData;
        $this->responseClassNamespace = $responseClassNamespace;
    }
    
    public function classNamespace(): ClassNamespace
    {
        return $this->classMetadata->namespace();
    }
    
    public function className(): ClassName
    {
        return $this->classMetadata->name();
    }
    
    public function interfaceNamespace(): ClassNamespace
    {
        return $this->interfaceNamespace;
    }
    
    public function parentClassNamespace(): ClassNamespace
    {
        return $this->parentClassNamespace;
    }
    
    public function classToHandleNamespace(): ClassNamespace
    {
        return $this->classToHandle->namespaceToHandle();
    }
    
    public function attributeToHandle(): AttributeName
    {
        return $this->classToHandle->attributeToHandle();
    }
    
    public function useCaseNamespace(): ClassNamespace
    {
        return $this->useCaseData->namespace();
    }
    
    public function useCaseName(): ClassName
    {
        return $this->useCaseData->name();
    }
    
    public function responseClassNamespace(): ClassNamespace
    {
        return $this->responseClassNamespace;
    }
}