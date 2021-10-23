<?php

namespace Mql21\DDDMakerBundle\Maker;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Finder\Class\CommandFinder;
use Mql21\DDDMakerBundle\Finder\Class\UseCaseFinder;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Generator\Handler\CommandHandlerGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Mql21\DDDMakerBundle\Renderer\HandlerRenderer;
use Mql21\DDDMakerBundle\Maker\Interaction\Response\UseCaseResponse;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeCommandHandler extends Command
{
    protected static $defaultName = 'ddd:cqs:make:command-handler';
    
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    private CommandFinder $commandFinder;
    private UseCaseFinder $useCaseFinder;
    private ConfigManager $configManager;
    private HandlerRenderer $renderer;
    private DDDClassBuilder $classBuilder;
    
    public function __construct(
        BoundedContextModuleLocator $boundedContextModuleLocator,
        CommandFinder $queryFinder,
        UseCaseFinder $useCaseFinder,
        ConfigManager $configManager,
        HandlerRenderer $renderer,
        DDDClassBuilder $classBuilder
    ) {
        $this->boundedContextModuleLocator = $boundedContextModuleLocator;
        $this->commandFinder = $queryFinder;
        $this->useCaseFinder = $useCaseFinder;
        $this->configManager = $configManager;
        $this->renderer = $renderer;
        $this->classBuilder = $classBuilder;
        
        parent::__construct(self::$defaultName);
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Creates a command handler in the Application layer.')
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
        
        $commandNameQuestion = new Question("<info> What command should the command handler listen to?</info>\n > ");
        $commandNameQuestion->setAutocompleterValues(
            $this->commandFinder->findIn($boundedContextName, $moduleName)
        );
        $questionHelper = $this->getHelper('question');
        
        $commandName = $questionHelper->ask($input, $output, $commandNameQuestion);
        
        $useCaseQuestion = new Question("<info> What use case should the command handler execute?</info>\n > ");
        $useCaseQuestion->setAutocompleterValues(
            $this->useCaseFinder->findIn($boundedContextName, $moduleName)
        );
        
        $useCaseNameResponse = new UseCaseResponse($questionHelper->ask($input, $output, $useCaseQuestion));
        
        $commandHandlerGenerator = new CommandHandlerGenerator(
            $useCaseNameResponse,
            $this->configManager,
            $this->renderer,
            $this->classBuilder
        );
        $commandHandlerGenerator->generate($boundedContextName, $moduleName, $commandName);
        
        $output->writeln("<info> Command handler {$commandName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}