<?php

namespace Tests;

use App\Database\Database;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    public function testMysqlConnection()
    {
        // Skip if MySQL is not configured
        if (!isset($_ENV['TEST_MYSQL']) || !$_ENV['TEST_MYSQL']) {
            $this->markTestSkipped('MySQL testing not enabled');
        }
        
        $config = [
            'driver' => 'mysql',
            'host' => $_ENV['MYSQL_HOST'] ?? 'localhost',
            'port' => $_ENV['MYSQL_PORT'] ?? 3306,
            'database' => $_ENV['MYSQL_DATABASE'] ?? 'test_blog',
            'username' => $_ENV['MYSQL_USERNAME'] ?? 'root',
            'password' => $_ENV['MYSQL_PASSWORD'] ?? 'root',
        ];
        
        Database::closeConnection(); // Ensure clean state
        $db = Database::getInstance($config);
        $this->assertInstanceOf(\PDO::class, $db);
    }
    
    public function testPostgresqlConnection()
    {
        // Skip if PostgreSQL is not configured
        if (!isset($_ENV['TEST_PGSQL']) || !$_ENV['TEST_PGSQL']) {
            $this->markTestSkipped('PostgreSQL testing not enabled');
        }
        
        $config = [
            'driver' => 'pgsql',
            'host' => $_ENV['PGSQL_HOST'] ?? 'localhost',
            'port' => $_ENV['PGSQL_PORT'] ?? 5432,
            'database' => $_ENV['PGSQL_DATABASE'] ?? 'test_blog',
            'username' => $_ENV['PGSQL_USERNAME'] ?? 'postgres',
            'password' => $_ENV['PGSQL_PASSWORD'] ?? 'postgres',
        ];
        
        Database::closeConnection(); // Ensure clean state
        $db = Database::getInstance($config);
        $this->assertInstanceOf(\PDO::class, $db);
    }
    
    public function testSqliteConnection()
    {
        $config = [
            'driver' => 'sqlite',
            'database' => ':memory:', // In-memory SQLite database for testing
        ];
        
        Database::closeConnection(); // Ensure clean state
        $db = Database::getInstance($config);
        $this->assertInstanceOf(\PDO::class, $db);
        
        // Test basic operations
        $db->exec('CREATE TABLE test (id INTEGER PRIMARY KEY, name TEXT)');
        $db->exec("INSERT INTO test (name) VALUES ('test')");
        
        $stmt = $db->query('SELECT * FROM test');
        $result = $stmt->fetch();
        
        $this->assertEquals('test', $result['name']);
    }
}