<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Models\User;

class CreateUserCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('user:create')
             ->setDescription('Create a new user');
    }

    public function execute(array $arguments = []): int
    {
        $this->output("Creating a new user...");
        
        // Get user input
        $username = $this->ask("Username: ");
        $email = $this->ask("Email: ");
        $password = $this->ask("Password: ");
        
        try {
            $userModel = new User();
            
            // Check if user already exists
            if ($userModel->findByEmail($email)) {
                $this->error("User with email {$email} already exists.");
                return 1;
            }
            
            if ($userModel->findByUsername($username)) {
                $this->error("User with username {$username} already exists.");
                return 1;
            }
            
            // Create user
            $userId = $userModel->createUser([
                'username' => $username,
                'email' => $email,
                'password' => $password
            ]);
            
            if ($userId) {
                $this->output("User created successfully with ID: {$userId}");
                return 0;
            } else {
                $this->error("Failed to create user.");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("Failed to create user: " . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Ask for user input
     */
    protected function ask(string $question): string
    {
        echo $question;
        return trim(fgets(STDIN));
    }
}