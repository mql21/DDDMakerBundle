## Context

To understand the goal of this project, please head to https://github.com/mql21/DDDMakerBundleExample.

## Installation

To install it, simply install the `mql21/ddd-maker-bundle` dependency:

```
composer require mql21/ddd-maker-bundle:dev-main
```

## Configuration

Register DDDMakerBundle's console commands to your `services.yaml` file:

```
    ...
    Mql21\DDDMakerBundle\Maker\MakeCommand: ~
    Mql21\DDDMakerBundle\Maker\MakeCommandHandler: ~
    Mql21\DDDMakerBundle\Maker\MakeQuery: ~
    Mql21\DDDMakerBundle\Maker\MakeQueryHandler: ~
    Mql21\DDDMakerBundle\Maker\MakeQueryResponse: ~
    Mql21\DDDMakerBundle\Maker\MakeValueObject: ~
    Mql21\DDDMakerBundle\Maker\MakeDomainEvent: ~
    Mql21\DDDMakerBundle\Maker\MakeUseCase: ~
    Mql21\DDDMakerBundle\Maker\MakeEventSubscriber: ~
    Mql21\DDDMakerBundle\Maker\MakeMissingDirectories: ~
```

## Use

Run your symfony console to check newly installed commands under the `ddd` namespace:

```
php bin/console
```

Then. simply run any of the listed commands and the console will start guiding you. Specification of bounded context and module is needed in every command. 

Example of usage:

```
php bin/console ddd:domain:make:event Notification Message
```