<?php

namespace Mql21\DDDMakerBundle\Finder\Class;

class UseCaseFinder extends ClassFinder
{
    public function type(): string
    {
        return 'use-case';
    }
}