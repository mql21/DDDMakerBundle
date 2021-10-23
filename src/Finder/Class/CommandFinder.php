<?php

namespace Mql21\DDDMakerBundle\Finder\Class;


class CommandFinder extends ClassFinder
{
    public function type(): string
    {
        return 'command';
    }
}