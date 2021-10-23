<?php

namespace Mql21\DDDMakerBundle\Generator\Contract;

interface DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $elementName): void;
}