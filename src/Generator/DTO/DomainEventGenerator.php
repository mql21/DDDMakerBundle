<?php

namespace Mql21\DDDMakerBundle\Generator\DTO;

class DomainEventGenerator extends DTOGenerator
{
    public function type(): string
    {
        return 'domain-event';
    }
}