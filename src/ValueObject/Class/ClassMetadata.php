<?php

namespace Mql21\DDDMakerBundle\ValueObject\Class;

class ClassMetadata
{
    private ClassNamespace $namespace;
    private ClassName $name;
    
    public function __construct(ClassNamespace $namespace, ClassName $name)
    {
        $this->namespace = $namespace;
        $this->name = $name;
    }
    
    public function namespace(): ClassNamespace
    {
        return $this->namespace;
    }
    
    public function name(): ClassName
    {
        return $this->name;
    }
}