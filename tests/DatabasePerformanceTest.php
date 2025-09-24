<?php

namespace Tests;

use App\Database\Database;
use PHPUnit\Framework\TestCase;

class DatabasePerformanceTest extends TestCase
{
    private $db;
    
    protected function setUp(): void
    {
        // Use in-memory SQLite for performance testing
        $config = [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ];
        
        Database::closeConnection(); // Ensure clean state
        $this->db = Database::getInstance($config);
        
        // Create test table
        $this->db->exec('
            CREATE TABLE performance_test (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');
    }
    
    public function testInsertPerformance()
    {
        $startTime = microtime(true);
        
        // Insert 1000 records
        for ($i = 0; $i < 1000; $i++) {
            $stmt = $this->db->prepare('
                INSERT INTO performance_test (name, email) 
                VALUES (:name, :email)
            ');
            $stmt->execute([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com'
            ]);
        }
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        
        // Should complete in less than 2 seconds
        $this->assertLessThan(2.0, $executionTime);
        
        // Verify all records were inserted
        $stmt = $this->db->query('SELECT COUNT(*) as count FROM performance_test');
        $result = $stmt->fetch();
        $this->assertEquals(1000, $result['count']);
    }
    
    public function testSelectPerformance()
    {
        // Insert test data first
        for ($i = 0; $i < 100; $i++) {
            $stmt = $this->db->prepare('
                INSERT INTO performance_test (name, email) 
                VALUES (:name, :email)
            ');
            $stmt->execute([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com'
            ]);
        }
        
        $startTime = microtime(true);
        
        // Select all records 100 times
        for ($i = 0; $i < 100; $i++) {
            $stmt = $this->db->query('SELECT * FROM performance_test');
            $results = $stmt->fetchAll();
            $this->assertCount(100, $results);
        }
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        
        // Should complete in less than 1 second
        $this->assertLessThan(1.0, $executionTime);
    }
    
    public function testPreparedStatementsPerformance()
    {
        // Insert test data first
        for ($i = 0; $i < 100; $i++) {
            $stmt = $this->db->prepare('
                INSERT INTO performance_test (name, email) 
                VALUES (:name, :email)
            ');
            $stmt->execute([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com'
            ]);
        }
        
        $startTime = microtime(true);
        
        // Use prepared statement 1000 times
        $stmt = $this->db->prepare('SELECT * FROM performance_test WHERE email = :email');
        
        for ($i = 0; $i < 1000; $i++) {
            $email = 'user' . ($i % 100) . '@example.com';
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch();
            $this->assertNotEmpty($result);
        }
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        
        // Should complete in less than 1 second
        $this->assertLessThan(1.0, $executionTime);
    }
    
    protected function tearDown(): void
    {
        // Clean up
        if ($this->db) {
            $this->db->exec('DROP TABLE IF EXISTS performance_test');
        }
    }
}