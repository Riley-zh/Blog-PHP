<?php

namespace App\Core;

use Monolog\Logger as MonoLogger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class Logger implements LoggerInterface
{
    protected MonoLogger $logger;

    public function __construct(?string $logPath = null, ?string $logLevel = null)
    {
        $logPath = $logPath ?? dirname(__DIR__, 2) . '/storage/logs/app.log';
        $logDir = dirname($logPath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $level = MonoLogger::DEBUG;
        if ($logLevel) {
            $map = [
                'debug' => MonoLogger::DEBUG,
                'info' => MonoLogger::INFO,
                'warning' => MonoLogger::WARNING,
                'error' => MonoLogger::ERROR,
            ];
            $level = $map[strtolower($logLevel)] ?? MonoLogger::DEBUG;
        }

        $this->logger = new MonoLogger('app');
        $this->logger->pushHandler(new StreamHandler($logPath, $level));
    }

    public function emergency($message, array $context = array()): void { $this->logger->emergency($message, $context); }
    public function alert($message, array $context = array()): void     { $this->logger->alert($message, $context); }
    public function critical($message, array $context = array()): void  { $this->logger->critical($message, $context); }
    public function error($message, array $context = array()): void     { $this->logger->error($message, $context); }
    public function warning($message, array $context = array()): void   { $this->logger->warning($message, $context); }
    public function notice($message, array $context = array()): void    { $this->logger->notice($message, $context); }
    public function info($message, array $context = array()): void      { $this->logger->info($message, $context); }
    public function debug($message, array $context = array()): void     { $this->logger->debug($message, $context); }
    public function log($level, $message, array $context = array()): void { $this->logger->log($level, $message, $context); }
}