<?php

namespace Mql21\DDDMakerBundle\Renderer;

use Mql21\DDDMakerBundle\ValueObject\DDDElement;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;

class DTORenderer implements DDDElementRenderer
{
    public function render(DDDElement $useCase): string
    {
        $namespace = new PhpNamespace($useCase->classNamespace()->namespace());
        $class = $namespace->addClass($useCase->className()->name());
        
        if (!empty($useCase->interfaceNamespace()->namespace())) {
            $namespace->addUse($useCase->interfaceNamespace()->namespace());
            $class->addImplement($useCase->interfaceNamespace()->namespace());
        }
        
        if (!empty($useCase->parentClassNamespace()->namespace())) {
            $namespace->addUse($useCase->parentClassNamespace()->namespace());
            $class->addExtend($useCase->parentClassNamespace()->namespace());
        }
        
        $constructor = $class->addMethod("__construct");
        $constructorBody = "";
        
        foreach ($useCase->attributes()->attributes() as $name => $type) {
            $this->addPropertyToClass($class, $name, $type);
            $constructorBody = $this->addPropertyToConstructor($constructor, $name, $type, $constructorBody);
            $this->addGetterMethod($class, $name, $type);
        }
        
        $constructor->setBody($constructorBody);
        
        return $this->renderNamespace($namespace);
    }
    
    private function renderNamespace(PhpNamespace $namespace): string
    {
        $printer = new PsrPrinter();
        return "<?php\n\ndeclare(strict_types=1);\n\n{$printer->printNamespace($namespace)}";
    }
    
    private function addPropertyToClass(ClassType $class, string $name, string $type): void
    {
        $class
            ->addProperty($name)
            ->setType($type)
            ->setPrivate();
    }
    
    private function addPropertyToConstructor(
        Method $constructor,
        string $name,
        string $type,
        string $constructorBody
    ): string {
        $constructor
            ->addParameter($name)
            ->setType($type);
        
        $constructorBody .= "\$this->{$name} = \${$name};\n";
        
        return $constructorBody;
    }
    
    private function addGetterMethod(ClassType $class, int|string $name, mixed $type): void
    {
        $class
            ->addMethod($name)
            ->setPublic()
            ->setReturnType($type)
            ->setBody("return \$this->{$name};");
    }
}