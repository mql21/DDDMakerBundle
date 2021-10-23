<?php

namespace Mql21\DDDMakerBundle\Finder\Class;

class DomainEventFinder extends ClassFinder
{
    public function type(): string
    {
        return 'domain-event';
    }
}