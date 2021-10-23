<?php

namespace Mql21\DDDMakerBundle\Exception;

use Throwable;

class ElementAlreadyExistsException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    
    public static function raise(string $element, string $boundedContextName, string $moduleName): void
    {
        throw new self("{$element} already exists in '{$moduleName}' module of '{$boundedContextName}' bounded context.");
    }
}