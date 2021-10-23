<?php

namespace Mql21\DDDMakerBundle\Renderer;

use Mql21\DDDMakerBundle\ValueObject\DDDElement;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;

class UseCaseRenderer implements DDDElementRenderer
{
    public function render(DDDElement $useCase): string
    {
        $namespace = new PhpNamespace($useCase->classMetadata()->namespace()->namespace());
        $class = $namespace->addClass($useCase->classMetadata()->name()->name());
        $class->setFinal();
        
        $this->addConstructorMethod($class);
        
        $this->addInvokeMethod($class, $useCase);
        
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
            ->setBody("// TODO: Inject repositories or other services here");
    }
    
    private function addInvokeMethod(ClassType $class, DDDElement $handlerClass): void
    {
        $class->addMethod("__invoke")
            ->setBody("// TODO: Implement use case here.")
            ->setReturnType("void");
        
    }
}