<?php

namespace Mql21\DDDMakerBundle\Maker;

use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Generator\ValueObjectGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Mql21\DDDMakerBundle\Renderer\ValueObjectRenderer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeValueObject extends Command
{
    protected static $defaultName = 'ddd:domain:make:value-object';
    
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    private ValueObjectRenderer $renderer;
    private DDDClassBuilder $classBuilder;
    
    public function __construct(BoundedContextModuleLocator $boundedContextModuleLocator, ValueObjectRenderer $renderer, DDDClassBuilder $classBuilder)
    {
        $this->boundedContextModuleLocator = $boundedContextModuleLocator;
        $this->renderer = $renderer;
        $this->classBuilder = $classBuilder;
        
        parent::__construct(self::$defaultName);
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Creates a value object in the Domain layer.')
            ->addArgument(
                'boundedContext',
                InputArgument::REQUIRED,
                'The name of the bounded context where Command will be saved into.'
            )
            ->addArgument(
                'module',
                InputArgument::REQUIRED,
                'The name of the module inside the bounded context where Command will be saved into.'
            );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $boundedContextName = $input->getArgument('boundedContext');
        $moduleName = $input->getArgument('module');
        
        $this->boundedContextModuleLocator->checkIfBoundedContextModuleExists($boundedContextName, $moduleName);
        
        $valueObjectNameQuestion = new Question("<info> What should the value object be called?</info>\n > ");
        $questionHelper = $this->getHelper('question');
        $valueObjectName = $questionHelper->ask($input, $output, $valueObjectNameQuestion);
        
        $valueObjectGenerator = new ValueObjectGenerator($this->renderer, $this->classBuilder);
        $valueObjectGenerator->generate($boundedContextName, $moduleName, $valueObjectName);
        
        $output->writeln("<info> Value object {$valueObjectName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}