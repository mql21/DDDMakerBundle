<?php

namespace Mql21\DDDMakerBundle\Maker;

use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Generator\UseCaseGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Mql21\DDDMakerBundle\Renderer\UseCaseRenderer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeUseCase extends Command
{
    protected static $defaultName = 'ddd:application:make:use-case';
    
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    private UseCaseRenderer $renderer;
    private DDDClassBuilder $classBuilder;
    
    public function __construct(
        BoundedContextModuleLocator $boundedContextModuleLocator,
        UseCaseRenderer $renderer,
        DDDClassBuilder $classBuilder
    ) {
        $this->boundedContextModuleLocator = $boundedContextModuleLocator;
        $this->renderer = $renderer;
        $this->classBuilder = $classBuilder;
        
        parent::__construct(self::$defaultName);
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Creates a use case (application service) in the Application layer.')
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
        
        $useCaseNameQuestion = new Question("<info> What should the use case be called?</info>\n > ");
        $questionHelper = $this->getHelper('question');
        $useCaseName = $questionHelper->ask($input, $output, $useCaseNameQuestion);
        
        $useCaseGenerator = new UseCaseGenerator($this->renderer, $this->classBuilder);
        $useCaseGenerator->generate($boundedContextName, $moduleName, $useCaseName);
        
        $output->writeln("<info> Use case {$useCaseName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}