<?php

namespace Mql21\DDDMakerBundle\Maker\Interaction\Response;

class UseCaseResponse
{
    private string $useCase;
    
    public function __construct(string $useCase)
    {
        $this->useCase = $useCase;
    }
    
    public function useCase(): string
    {
        return $this->useCase;
    }
}