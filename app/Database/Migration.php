<?php

namespace App\Database;

use PDO;

class Migration
{
    protected PDO $pdo;

    public function __construct()
    {
        // Get database configuration from environment
        $config = [
            'driver' => $_ENV['DB_DRIVER'] ?? 'sqlite',
            'database' => $_ENV['DB_DATABASE'] ?? './database/blog.sqlite',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? 3306,
            'username' => $_ENV['DB_USERNAME'] ?? null,
            'password' => $_ENV['DB_PASSWORD'] ?? null,
        ];
        
        $this->pdo = Database::getInstance($config);
    }

    /**
     * Run all pending migrations
     */
    public function runMigrations(): void
    {
        // Create migrations table if it doesn't exist
        $this->createMigrationsTable();
        
        // Get already run migrations
        $runMigrations = $this->getRunMigrations();
        
        // Get all migration files
        $migrationFiles = glob(dirname(__DIR__, 2) . '/database/migrations/*.php');
        
        foreach ($migrationFiles as $file) {
            $filename = basename($file);
            $migrationName = substr($filename, 0, -4); // Remove .php extension
            
            // Skip if already run
            if (in_array($migrationName, $runMigrations)) {
                continue;
            }
            
            // Require the migration file
            require_once $file;
            
            // Get the migration class name
            $className = 'Migration_' . str_replace('.', '_', $migrationName);
            if (!class_exists($className)) {
                $className = 'Migration_' . str_replace(['-', '.', ' '], '_', $migrationName);
            }
            
            if (class_exists($className)) {
                $migration = new $className($this->pdo);
                if (method_exists($migration, 'up')) {
                    $migration->up();
                    $this->markMigrationAsRun($migrationName);
                    echo "Ran migration: {$migrationName}\n";
                }
            }
        }
    }

    /**
     * Create the migrations table
     */
    protected function createMigrationsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            migration VARCHAR(255) NOT NULL,
            batch INTEGER NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $this->pdo->exec($sql);
    }

    /**
     * Get list of already run migrations
     */
    protected function getRunMigrations(): array
    {
        $stmt = $this->pdo->query("SELECT migration FROM migrations ORDER BY batch, migration");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Mark a migration as run
     */
    protected function markMigrationAsRun(string $migrationName): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, 1)");
        $stmt->execute([$migrationName]);
    }
}