<?php

namespace Mql21\DDDMakerBundle\Renderer;

use Mql21\DDDMakerBundle\ValueObject\DDDElement;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;

class ValueObjectRenderer implements DDDElementRenderer
{
    public function render(DDDElement $useCase): string
    {
        $namespace = new PhpNamespace($useCase->classMetadata()->namespace()->namespace());
        $class = $namespace->addClass($useCase->classMetadata()->name()->name());
        $class->setFinal();
        
        $this->addConstructorMethod($class);
        
        $this->addCreateMethod($class);
        
        return $this->renderNamespace($namespace);
    }
    
    private function renderNamespace(PhpNamespace $namespace): string
    {
        $printer = new PsrPrinter();
        return "<?php\n\ndeclare(strict_types=1);\n\n{$printer->printNamespace($namespace)}";
    }
    
    private function addConstructorMethod(ClassType $class): void
    {
        $class->addMethod("__construct")
            ->setBody("// TODO: Init value here");
    }
    
    private function addCreateMethod(ClassType $class): void
    {
        $class->addMethod("create")
            ->setStatic(true)
            ->setBody("// TODO: Implement create factory method here.")
            ->setReturnType("self");
        
    }
}