<?php

namespace App\Models;

class Category extends Model
{
    protected string $table = 'categories';
    protected string $primaryKey = 'id';
    
    // Define fillable fields for mass assignment
    protected array $fillable = [
        'name',
        'slug',
        'description'
    ];
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get category by slug
     */
    public function getBySlug(string $slug)
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }
    
    /**
     * Get all categories with post counts
     */
    public function getAllWithPostCount(): array
    {
        $sql = "SELECT c.*, COUNT(pc.post_id) as post_count 
                FROM {$this->table} c 
                LEFT JOIN post_categories pc ON c.id = pc.category_id 
                GROUP BY c.id 
                ORDER BY c.name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}