<?php

namespace Mql21\DDDMakerBundle\Maker;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Generator\DTO\CommandGenerator;
use Mql21\DDDMakerBundle\Maker\Interaction\Question\DTOAttributeQuestioner;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeCommand extends Command
{
    protected static $defaultName = 'ddd:cqs:make:command';
    
    private CommandGenerator $commandGenerator;
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    private DTOAttributeQuestioner $attributeQuestioner;
    private ConfigManager $configManager;
    private DDDClassBuilder $classBuilder;
    
    public function __construct(
        BoundedContextModuleLocator $boundedContextModuleLocator,
        ConfigManager $configManager,
        DDDClassBuilder $classBuilder,
        DTOAttributeQuestioner $dtoAttributeInteractor
    ) {
        $this->boundedContextModuleLocator = $boundedContextModuleLocator;
        $this->configManager = $configManager;
        $this->attributeQuestioner = $dtoAttributeInteractor;
        $this->classBuilder = $classBuilder;
        
        parent::__construct(self::$defaultName);
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Creates a command in the Application layer.')
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
        
        // Ask for command name and create it
        $commandNameQuestion = new Question("<info> What should the command be called?</info>\n > ");
        $questionHelper = $this->getHelper('question');
        $commandName = $questionHelper->ask($input, $output, $commandNameQuestion);
        
        $this->commandGenerator = new CommandGenerator(
            $this->attributeQuestioner->ask($input, $output, $questionHelper),
            $this->configManager,
            $this->classBuilder
        );
        
        $this->commandGenerator->generate($boundedContextName, $moduleName, $commandName);
        
        $output->writeln("<info> Command {$commandName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}