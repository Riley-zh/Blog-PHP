<?php

namespace App\Console;

class CommandManager
{
    protected array $commands = [];

    /**
     * Register a command
     */
    public function addCommand(Command $command): void
    {
        $this->commands[$command->getName()] = $command;
    }

    /**
     * Get all registered commands
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * Get a command by name
     */
    public function getCommand(string $name): ?Command
    {
        return $this->commands[$name] ?? null;
    }

    /**
     * Run a command
     */
    public function run(string $commandName, array $arguments = []): int
    {
        if (!isset($this->commands[$commandName])) {
            echo "Command '{$commandName}' not found." . PHP_EOL;
            echo "Available commands:" . PHP_EOL;
            foreach ($this->commands as $name => $command) {
                echo "  {$name} - {$command->getDescription()}" . PHP_EOL;
            }
            return 1;
        }

        $command = $this->commands[$commandName];
        return $command->execute($arguments);
    }

    /**
     * List all available commands
     */
    public function listCommands(): void
    {
        echo "Available commands:" . PHP_EOL;
        foreach ($this->commands as $name => $command) {
            echo "  {$name} - {$command->getDescription()}" . PHP_EOL;
        }
    }
}