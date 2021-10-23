<?php

namespace Mql21\DDDMakerBundle\Maker;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Finder\Class\DomainEventFinder;
use Mql21\DDDMakerBundle\Finder\Class\UseCaseFinder;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Generator\Handler\DomainEventSubscriberGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Mql21\DDDMakerBundle\Renderer\HandlerRenderer;
use Mql21\DDDMakerBundle\Maker\Interaction\Response\UseCaseResponse;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeEventSubscriber extends Command
{
    protected static $defaultName = 'ddd:application:make:event-subscriber';
    
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    private DomainEventFinder $eventFinder;
    private UseCaseFinder $useCaseFinder;
    private ConfigManager $configManager;
    private DDDClassBuilder $DDDClassBuilder;
    private HandlerRenderer $renderer;
    
    public function __construct(
        BoundedContextModuleLocator $boundedContextModuleLocator,
        DomainEventFinder $eventFinder,
        UseCaseFinder $useCaseFinder,
        ConfigManager $configManager,
        DDDClassBuilder $DDDClassBuilder,
        HandlerRenderer $renderer
    ) {
        $this->boundedContextModuleLocator = $boundedContextModuleLocator;
        $this->eventFinder = $eventFinder;
        $this->useCaseFinder = $useCaseFinder;
        $this->configManager = $configManager;
        $this->DDDClassBuilder = $DDDClassBuilder;
        $this->renderer = $renderer;
        
        parent::__construct(self::$defaultName);
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Creates an event subscriber in the Application layer.')
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
        
        $eventNameQuestion = new Question("<info> What event should the subscriber be subscribed to?</info>\n > ");
        $eventNameQuestion->setAutocompleterValues(
            $this->eventFinder->findIn($boundedContextName, $moduleName)
        );
        $questionHelper = $this->getHelper('question');
        
        $eventName = $questionHelper->ask($input, $output, $eventNameQuestion);
        
        $useCaseQuestion = new Question("<info> What use case should the subscriber execute?</info>\n > ");
        $useCaseQuestion->setAutocompleterValues(
            $this->useCaseFinder->findIn($boundedContextName, $moduleName)
        );
        $questionHelper = $this->getHelper('question');
        
        $domainEventSubscriberGenerator = new DomainEventSubscriberGenerator(
            new UseCaseResponse($questionHelper->ask($input, $output, $useCaseQuestion)),
            $this->configManager,
            $this->renderer,
            $this->DDDClassBuilder
        );
        $domainEventSubscriberGenerator->generate($boundedContextName, $moduleName, $eventName);
        
        $output->writeln("<info> Event subscriber for {$eventName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}