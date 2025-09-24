<?php

namespace App\Models;

class PostCategory extends Model
{
    protected string $table = 'post_categories';
    protected string $primaryKey = 'id';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get categories for a post
     */
    public function getCategoriesForPost(int $postId): array
    {
        $sql = "SELECT c.* FROM categories c 
                JOIN {$this->table} pc ON c.id = pc.category_id 
                WHERE pc.post_id = :post_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get posts for a category
     */
    public function getPostsForCategory(int $categoryId): array
    {
        $sql = "SELECT p.* FROM posts p 
                JOIN {$this->table} pc ON p.id = pc.post_id 
                WHERE pc.category_id = :category_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetchAll();
    }
}