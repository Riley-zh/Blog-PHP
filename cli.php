#!/usr/bin/env php
<?php

require_once __DIR__ . '/bootstrap/app.php';

use App\Console\CommandManager;
use App\Console\Commands\HelpCommand;
use App\Console\Commands\MigrateCommand;
use App\Console\Commands\CreateUserCommand;

// Initialize command manager
$commandManager = new CommandManager();

// Register commands
$commandManager->addCommand(new HelpCommand());
$commandManager->addCommand(new MigrateCommand());
$commandManager->addCommand(new CreateUserCommand());

// Make it globally accessible for HelpCommand
global $commandManager;

// Get command and arguments
if ($argc < 2) {
    $commandManager->listCommands();
    exit(0);
}

$commandName = $argv[1];
$arguments = array_slice($argv, 2);

// Run command
exit($commandManager->run($commandName, $arguments));
