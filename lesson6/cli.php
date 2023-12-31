<?php

use src\Commands\Arguments;
use src\Commands\CreateUserCommand;
use src\Exceptions\CommandException;

$container = require __DIR__ . '/bootstrap.php';
$command = $container->get(CreateUserCommand::class);

try {
    $command->handle(Arguments::fromArgv($argv));
} catch (CommandException $error) {
    echo "{$error->getMessage()}\n";
}