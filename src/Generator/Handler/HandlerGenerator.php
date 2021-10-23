<?php

namespace Mql21\DDDMakerBundle\Generator\Handler;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Renderer\HandlerRenderer;
use Mql21\DDDMakerBundle\Maker\Interaction\Response\UseCaseResponse;

class HandlerGenerator
{
    protected UseCaseResponse $useCaseResponse;
    protected ConfigManager $configManager;
    protected HandlerRenderer $renderer;
    protected DDDClassBuilder $classBuilder;
    
    public function __construct(
        UseCaseResponse $useCaseResponse,
        ConfigManager $configManager,
        HandlerRenderer $renderer,
        DDDClassBuilder $classBuilder
    ) {
        $this->useCaseResponse = $useCaseResponse;
        $this->configManager = $configManager;
        $this->renderer = $renderer;
        $this->classBuilder = $classBuilder;
    }
}