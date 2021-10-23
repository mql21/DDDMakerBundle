<?php

namespace Mql21\DDDMakerBundle\ValueObject;

use Mql21\DDDMakerBundle\ValueObject\Class\ClassAttributes;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassName;
use Mql21\DDDMakerBundle\ValueObject\Class\ClassNamespace;

class DTOClass extends DDDElement
{
    private ClassNamespace $classNamespace;
    private ClassName $className;
    private ClassNamespace $interfaceNamespace;
    private ClassNamespace $parentClassNamespace;
    private ClassAttributes $attributes;
    
    public function __construct(
        ClassNamespace $classNamespace,
        ClassName $className,
        ClassNamespace $interfaceNamespace,
        ClassNamespace $parentClassNamespace,
        ClassAttributes $attributes
    ) {
        $this->classNamespace = $classNamespace;
        $this->className = $className;
        $this->interfaceNamespace = $interfaceNamespace;
        $this->attributes = $attributes;
        $this->parentClassNamespace = $parentClassNamespace;
    }
    
    public function classNamespace(): ClassNamespace
    {
        return $this->classNamespace;
    }
    
    public function className(): ClassName
    {
        return $this->className;
    }
    
    public function interfaceNamespace(): ClassNamespace
    {
        return $this->interfaceNamespace;
    }
    
    public function parentClassNamespace(): ClassNamespace
    {
        return $this->parentClassNamespace;
    }
    
    public function attributes(): ClassAttributes
    {
        return $this->attributes;
    }
}