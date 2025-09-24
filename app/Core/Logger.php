<?php

namespace App\Core;

class Logger
{
    protected string $logPath;
    protected string $logLevel;

    public function __construct(?string $logPath = null, ?string $logLevel = null)
    {
        $this->logPath = $logPath ?? dirname(__DIR__, 2) . '/storage/logs/app.log';
        $this->logLevel = $logLevel ?? 'debug';
        
        // Create log directory if it doesn't exist
        $logDir = dirname($this->logPath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    /**
     * Log a debug message
     */
    public function debug(string $message, array $context = []): void
    {
        $this->log('debug', $message, $context);
    }

    /**
     * Log an info message
     */
    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    /**
     * Log a warning message
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    /**
     * Log an error message
     */
    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    /**
     * Log a message
     */
    protected function log(string $level, string $message, array $context = []): void
    {
        // Check if log level is enabled
        if (!$this->isLevelEnabled($level)) {
            return;
        }

        // Format the message
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[{$timestamp}] " . strtoupper($level) . ": {$message}";
        
        if (!empty($context)) {
            $formattedMessage .= ' ' . json_encode($context);
        }
        
        $formattedMessage .= PHP_EOL;

        // Write to log file
        file_put_contents($this->logPath, $formattedMessage, FILE_APPEND | LOCK_EX);
    }

    /**
     * Check if log level is enabled
     */
    protected function isLevelEnabled(string $level): bool
    {
        $levels = [
            'debug' => 0,
            'info' => 1,
            'warning' => 2,
            'error' => 3
        ];

        $currentLevel = $levels[$this->logLevel] ?? 0;
        $messageLevel = $levels[$level] ?? 0;

        return $messageLevel >= $currentLevel;
    }
}