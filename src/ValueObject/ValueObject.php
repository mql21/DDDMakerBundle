<?php

namespace Mql21\DDDMakerBundle\ValueObject;

use Mql21\DDDMakerBundle\ValueObject\Class\ClassMetadata;

class ValueObject extends DDDElement
{
    private ClassMetadata $classMetadata;
    
    public function __construct(ClassMetadata $classMetadata)
    {
        $this->classMetadata = $classMetadata;
    }
    
    public static function create(ClassMetadata $classMetadata): self
    {
        return new self($classMetadata);
    }
    
    public function classMetadata(): ClassMetadata
    {
        return $this->classMetadata;
    }
}