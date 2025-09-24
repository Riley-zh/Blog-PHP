<?php

namespace App\Console;

abstract class Command
{
    protected string $name;
    protected string $description;

    public function __construct()
    {
        $this->configure();
    }

    /**
     * Configure the command
     */
    abstract protected function configure(): void;

    /**
     * Execute the command
     */
    abstract public function execute(array $arguments = []): int;

    /**
     * Set the command name
     */
    protected function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the command description
     */
    protected function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get the command name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the command description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Write output to console
     */
    protected function output(string $message, bool $newLine = true): void
    {
        echo $message;
        if ($newLine) {
            echo PHP_EOL;
        }
    }

    /**
     * Write error output to console
     */
    protected function error(string $message): void
    {
        fwrite(STDERR, $message . PHP_EOL);
    }
}