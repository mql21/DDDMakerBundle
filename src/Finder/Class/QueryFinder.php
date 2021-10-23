<?php

namespace Mql21\DDDMakerBundle\Finder\Class;

class QueryFinder extends ClassFinder
{
    public function type(): string
    {
        return 'query';
    }
}