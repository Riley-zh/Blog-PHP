<?php

namespace App\Models;

class Tag extends Model
{
    protected string $table = 'tags';
    protected string $primaryKey = 'id';
    
    // Define fillable fields for mass assignment
    protected array $fillable = [
        'name',
        'slug'
    ];
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get tag by slug
     */
    public function getBySlug(string $slug)
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }
    
    /**
     * Get all tags with post counts
     */
    public function getAllWithPostCount(): array
    {
        $sql = "SELECT t.*, COUNT(pt.post_id) as post_count 
                FROM {$this->table} t 
                LEFT JOIN post_tags pt ON t.id = pt.tag_id 
                GROUP BY t.id 
                ORDER BY t.name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}