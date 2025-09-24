<?php

namespace App\Core;

class Search
{
    protected array $models = [];
    protected array $fields = [];
    protected array $weights = [];

    /**
     * Add a model to search in
     */
    public function addModel(string $modelClass, array $fields, array $weights = []): self
    {
        $this->models[] = $modelClass;
        $this->fields[$modelClass] = $fields;
        $this->weights[$modelClass] = $weights;
        return $this;
    }

    /**
     * Perform a search across all added models
     */
    public function search(string $query, int $limit = 15): array
    {
        $results = [];
        
        foreach ($this->models as $modelClass) {
            $model = new $modelClass();
            $fields = $this->fields[$modelClass];
            $weights = $this->weights[$modelClass] ?? [];
            
            $modelResults = $this->searchInModel($model, $fields, $weights, $query, $limit);
            $results = array_merge($results, $modelResults);
        }
        
        // Sort results by relevance score
        usort($results, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        // Limit results
        return array_slice($results, 0, $limit);
    }

    /**
     * Search in a specific model
     */
    protected function searchInModel($model, array $fields, array $weights, string $query, int $limit): array
    {
        $table = $model->table;
        $primaryKey = $model->primaryKey;
        
        // Build search conditions
        $conditions = [];
        $params = [];
        
        foreach ($fields as $field) {
            $conditions[] = "{$field} LIKE :query";
            $params[$field] = "%{$query}%";
        }
        
        $whereClause = implode(' OR ', $conditions);
        $sql = "SELECT *, '{$table}' as search_type FROM {$table} WHERE {$whereClause} LIMIT :limit";
        
        $stmt = $model->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        $rows = $stmt->fetchAll();
        
        // Calculate relevance scores
        $results = [];
        foreach ($rows as $row) {
            $score = $this->calculateRelevanceScore($row, $fields, $weights, $query);
            $results[] = [
                'data' => $row,
                'score' => $score,
                'type' => $table
            ];
        }
        
        return $results;
    }

    /**
     * Calculate relevance score for a row
     */
    protected function calculateRelevanceScore(array $row, array $fields, array $weights, string $query): float
    {
        $score = 0;
        $query = strtolower($query);
        
        foreach ($fields as $field) {
            if (!isset($row[$field])) {
                continue;
            }
            
            $fieldValue = strtolower($row[$field]);
            $weight = $weights[$field] ?? 1.0;
            
            // Exact match gets highest score
            if ($fieldValue === $query) {
                $score += 10 * $weight;
            }
            // Exact word match
            elseif (preg_match('/\b' . preg_quote($query, '/') . '\b/', $fieldValue)) {
                $score += 5 * $weight;
            }
            // Partial match
            elseif (strpos($fieldValue, $query) !== false) {
                $score += 1 * $weight;
            }
        }
        
        return $score;
    }

    /**
     * Perform a full-text search (if supported by the database)
     */
    public function fullTextSearch(string $query, array $tablesAndColumns, int $limit = 15): array
    {
        $results = [];
        
        foreach ($tablesAndColumns as $table => $columns) {
            $modelClass = $this->getModelClassForTable($table);
            if ($modelClass) {
                $model = new $modelClass();
                
                // For SQLite, we'll use a simple LIKE search
                // For MySQL/PostgreSQL, you could implement full-text search
                $conditions = [];
                $params = [];
                
                foreach ($columns as $column) {
                    $conditions[] = "{$column} LIKE :query";
                    $params[$column] = "%{$query}%";
                }
                
                $whereClause = implode(' OR ', $conditions);
                $sql = "SELECT *, '{$table}' as search_type FROM {$table} WHERE {$whereClause} LIMIT :limit";
                
                $stmt = $model->db->prepare($sql);
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value);
                }
                $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
                $stmt->execute();
                
                $rows = $stmt->fetchAll();
                
                foreach ($rows as $row) {
                    $results[] = [
                        'data' => $row,
                        'type' => $table
                    ];
                }
            }
        }
        
        return $results;
    }

    /**
     * Get model class for a table
     */
    protected function getModelClassForTable(string $table): ?string
    {
        // This is a simplified mapping
        // In a real application, you would have a more sophisticated way to map tables to models
        $mapping = [
            'posts' => 'App\\Models\\Post',
            'users' => 'App\\Models\\User',
            'categories' => 'App\\Models\\Category',
            'tags' => 'App\\Models\\Tag',
        ];
        
        return $mapping[$table] ?? null;
    }
}