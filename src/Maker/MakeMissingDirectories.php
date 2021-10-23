<?php

namespace Mql21\DDDMakerBundle\Maker;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Generator\MissingDirectoriesGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeMissingDirectories extends Command
{
    protected static $defaultName = 'ddd:make:missing-directories';
    
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    private ConfigManager $configManager;
    
    public function __construct(BoundedContextModuleLocator $boundedContextModuleLocator, ConfigManager $configManager)
    {
        $this->boundedContextModuleLocator = $boundedContextModuleLocator;
        $this->configManager = $configManager;
    
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
        
        $missingDirectoriesGenerator = new MissingDirectoriesGenerator($this->configManager);
        $missingDirectoriesGenerator->generate($boundedContextName, $moduleName);
        
        $output->writeln(
            "<info> Missing directories for module {$moduleName} of {$boundedContextName} bounded context have been successfully created! </info>\n\n"
        );
        
        return Command::SUCCESS;
    }
}