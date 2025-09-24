<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Database\Migration;

class MigrateCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('migrate')
             ->setDescription('Run database migrations');
    }

    public function execute(array $arguments = []): int
    {
        $this->output("Running migrations...");
        
        try {
            $migration = new Migration();
            $migration->runMigrations();
            
            $this->output("Migrations completed successfully.");
            return 0;
        } catch (\Exception $e) {
            $this->error("Migration failed: " . $e->getMessage());
            return 1;
        }
    }
}