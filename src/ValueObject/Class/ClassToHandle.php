<?php

namespace Mql21\DDDMakerBundle\ValueObject\Class;

class ClassToHandle
{
    private ClassNamespace $namespaceToHandle;
    private ClassName $nameToHandle;
    private AttributeName $attributeToHandle;
    
    public function __construct(ClassNamespace $namespaceToHandle, ClassName $nameToHandle, AttributeName $attributeToHandle)
    {
        $this->namespaceToHandle = $namespaceToHandle;
        $this->nameToHandle = $nameToHandle;
        $this->attributeToHandle = $attributeToHandle;
    }
    
    public function namespaceToHandle(): ClassNamespace
    {
        return $this->namespaceToHandle;
    }
    
    public function nameToHandle(): ClassName
    {
        return $this->nameToHandle;
    }
    
    public function attributeToHandle(): AttributeName
    {
        return $this->attributeToHandle;
    }
}