<?php

namespace Mql21\DDDMakerBundle\Renderer;

use Mql21\DDDMakerBundle\ValueObject\DDDElement;
use Mql21\DDDMakerBundle\ValueObject\HandlerClass;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;

class HandlerRenderer implements DDDElementRenderer
{
    public function render(DDDElement $useCase): string
    {
        $namespace = new PhpNamespace($useCase->classNamespace()->namespace());
        $class = $namespace->addClass($useCase->className()->name());
        $class->setFinal();
    
        if (!empty($useCase->interfaceNamespace()->namespace())) {
            $namespace->addUse($useCase->interfaceNamespace()->namespace());
            $class->addImplement($useCase->interfaceNamespace()->namespace());
        }
    
        if (!empty($useCase->parentClassNamespace()->namespace())) {
            $namespace->addUse($useCase->parentClassNamespace()->namespace());
            $class->addExtend($useCase->parentClassNamespace()->namespace());
        }
    
        if (!empty($useCase->responseClassNamespace()->namespace())) {
            $namespace->addUse($useCase->responseClassNamespace()->namespace());
        }
    
        $namespace->addUse($useCase->classToHandleNamespace()->namespace());
        $namespace->addUse($useCase->useCaseNamespace()->namespace());
    
        $class
            ->addProperty("useCase")
            ->setType($useCase->useCaseNamespace()->namespace());
    
        $this->addConstructorMethod($class, $useCase);
    
        $this->addInvokeMethod($class, $useCase);
    
        return $this->renderNamespace($namespace);
    }
    
    private function renderNamespace(PhpNamespace $namespace): string
    {
        $printer = new PsrPrinter();
        return "<?php\n\ndeclare(strict_types=1);\n\n{$printer->printNamespace($namespace)}";
    }
    
    private function addConstructorMethod(ClassType $class, HandlerClass $handlerClass): void
    {
        $constructMethod = $class->addMethod("__construct");
        $constructMethod
            ->addParameter("useCase")
            ->setType($handlerClass->useCaseNamespace()->namespace());
        $constructMethod->setBody("\$this->useCase = \$useCase;");
    }
    
    private function addInvokeMethod(ClassType $class, DDDElement $handlerClass): void
    {
        $invokeMethod = $class->addMethod("__invoke");
        $invokeMethod
            ->addParameter($handlerClass->attributeToHandle()->name())
            ->setType($handlerClass->classToHandleNamespace()->namespace());
        
        $returnType = "void";
        $returnResponse = "";
        if (!empty($handlerClass->responseClassNamespace()->namespace())) {
            $returnType = $handlerClass->responseClassNamespace()->namespace();
            $returnResponse = "return ";
        }
        
        $invokeMethod->setBody("{$returnResponse}\$this->useCase->__invoke();");
        $invokeMethod->setReturnType($returnType);
    }
}