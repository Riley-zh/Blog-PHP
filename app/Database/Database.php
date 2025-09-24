<?php

namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private array $config;

    private function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }

    /**
     * Get the database instance
     */
    public static function getInstance(array $config = []): PDO
    {
        if (self::$instance === null) {
            new self($config);
        }

        return self::$instance;
    }

    /**
     * Connect to the database
     */
    private function connect(): void
    {
        $dsn = $this->getDsn();
        
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true, // Enable persistent connections for better performance
            ];
            
            // Handle different database drivers
            $driver = $this->config['driver'] ?? 'sqlite';
            
            switch ($driver) {
                case 'sqlite':
                    self::$instance = new PDO($dsn, null, null, $options);
                    break;
                    
                case 'mysql':
                case 'pgsql':
                default:
                    self::$instance = new PDO(
                        $dsn,
                        $this->config['username'] ?? null,
                        $this->config['password'] ?? null,
                        $options
                    );
                    break;
            }
        } catch (PDOException $e) {
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Get the DSN string
     */
    private function getDsn(): string
    {
        $driver = $this->config['driver'] ?? 'sqlite';
        $database = $this->config['database'] ?? '';

        switch ($driver) {
            case 'mysql':
                $host = $this->config['host'] ?? 'localhost';
                $port = $this->config['port'] ?? 3306;
                $charset = $this->config['charset'] ?? 'utf8mb4';
                return "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";
            
            case 'pgsql':
                $host = $this->config['host'] ?? 'localhost';
                $port = $this->config['port'] ?? 5432;
                return "pgsql:host={$host};port={$port};dbname={$database}";
            
            case 'sqlite':
            default:
                // For SQLite, database can be a file path or :memory: for in-memory database
                // Ensure the database directory exists for file-based SQLite
                if ($database !== ':memory:' && !empty($database)) {
                    $dir = dirname($database);
                    if (!empty($dir) && !is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                }
                return "sqlite:{$database}";
        }
    }

    /**
     * Close the database connection
     */
    public static function closeConnection(): void
    {
        self::$instance = null;
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Prevent unserializing
     */
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }
}