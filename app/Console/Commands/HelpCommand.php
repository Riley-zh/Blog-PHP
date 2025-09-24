<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Console\CommandManager;

class HelpCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('help')
             ->setDescription('Display help information');
    }

    public function execute(array $arguments = []): int
    {
        global $commandManager;
        
        $this->output("Modern PHP Blog CLI");
        $this->output("==================");
        $this->output("");
        
        if (isset($arguments[0])) {
            // Show specific command help
            $command = $commandManager->getCommand($arguments[0]);
            if ($command) {
                $this->output("Command: " . $command->getName());
                $this->output("Description: " . $command->getDescription());
            } else {
                $this->error("Command '{$arguments[0]}' not found.");
                return 1;
            }
        } else {
            // Show all commands
            $commandManager->listCommands();
        }
        
        return 0;
    }
}