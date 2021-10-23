<?php

namespace Mql21\DDDMakerBundle\Renderer;

use Mql21\DDDMakerBundle\ValueObject\DDDElement;

interface DDDElementRenderer
{
    public function render(DDDElement $element): string;
}