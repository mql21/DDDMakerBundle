<?php

namespace Mql21\DDDMakerBundle\ValueObject\Class;

class AttributeName
{
    private string $name;
    
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    
    public static function create(string $name): self
    {
        return new self($name);
    }
    
    public function name(): string
    {
        return $this->name;
    }
}