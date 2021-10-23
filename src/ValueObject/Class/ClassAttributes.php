<?php

namespace Mql21\DDDMakerBundle\ValueObject\Class;

class ClassAttributes
{
    private array $attributes;
    
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }
    
    public function attributes(): array
    {
        return $this->attributes;
    }
}