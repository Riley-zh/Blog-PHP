<?php

namespace App\Models;

use App\Database\Database;
use PDO;

abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $hidden = [];
    protected array $casts = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find a record by ID
     */
    public function find(int $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Find all records
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Find records by conditions
     */
    public function where(array $conditions): array
    {
        $whereClause = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            $whereClause[] = "{$column} = :{$column}";
            $params[$column] = $value;
        }
        
        $whereStr = implode(' AND ', $whereClause);
        $sql = "SELECT * FROM {$this->table} WHERE {$whereStr}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Create a new record
     */
    public function create(array $data): int
    {
        // Filter data based on fillable fields
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }
        
        // Remove primary key if present in data
        unset($data[$this->primaryKey]);
        
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        try {
            if (!$this->db->inTransaction()) {
                $this->db->beginTransaction();
                $started = true;
            } else {
                $started = false;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);
            $id = (int) $this->db->lastInsertId();

            if ($started) {
                $this->db->commit();
            }

            return $id;
        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Update a record
     */
    public function update(int $id, array $data): bool
    {
        // Filter data based on fillable fields
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }
        
        // Remove primary key if present in data
        unset($data[$this->primaryKey]);
        
        $setClause = [];
        $params = [];
        
        foreach ($data as $column => $value) {
            $setClause[] = "{$column} = :{$column}";
            $params[$column] = $value;
        }
        
        if (empty($setClause)) {
            return false;
        }
        
        $params[$this->primaryKey] = $id;
        $setStr = implode(', ', $setClause);
        
        $sql = "UPDATE {$this->table} SET {$setStr} WHERE {$this->primaryKey} = :{$this->primaryKey}";
        try {
            if (!$this->db->inTransaction()) {
                $this->db->beginTransaction();
                $started = true;
            } else {
                $started = false;
            }

            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);

            if ($started) {
                $this->db->commit();
            }

            return $result;
        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Delete a record
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        try {
            if (!$this->db->inTransaction()) {
                $this->db->beginTransaction();
                $started = true;
            } else {
                $started = false;
            }

            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(['id' => $id]);

            if ($started) {
                $this->db->commit();
            }

            return $result;
        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Find records with pagination
     */
    public function paginate(int $page = 1, int $limit = 15): array
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Count all records
     */
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }

    /**
     * Count records with conditions
     */
    public function countWhere(array $conditions): int
    {
        $whereClause = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            $whereClause[] = "{$column} = :{$column}";
            $params[$column] = $value;
        }
        
        $whereStr = implode(' AND ', $whereClause);
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$whereStr}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }

    /**
     * Find records with order
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        // This is a simplified implementation
        // In a more complex system, you would store this state
        return $this;
    }
}