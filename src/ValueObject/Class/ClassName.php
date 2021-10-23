<?php

namespace Mql21\DDDMakerBundle\ValueObject\Class;

class ClassName
{
    private ?string $name;
    
    public function __construct(?string $name)
    {
        $this->name = $name;
    }
    
    public static function create(string $elementClassName): self
    {
        return new self($elementClassName);
    }
    
    public function name(): string
    {
        return $this->name;
    }
}