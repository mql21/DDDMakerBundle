<?php

namespace Mql21\DDDMakerBundle\ValueObject\Class;

class ClassNamespace
{
    private ?string $namespace;
    
    public function __construct(?string $namespace)
    {
        $this->namespace = $namespace;
    }
    
    public static function create(string $namespace): self
    {
        return new self($namespace);
    }
    
    public function namespace(): ?string
    {
        return $this->namespace;
    }
}