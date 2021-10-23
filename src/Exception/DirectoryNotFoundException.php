<?php

namespace Mql21\DDDMakerBundle\Exception;

use Throwable;

class DirectoryNotFoundException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    
    public static function raise(string $directory): void
    {
        throw new self("Directory '{$directory}' does not exist. Please run ddd:make:missing-directories and try it again.");
    }
}