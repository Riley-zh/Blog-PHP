<?php

namespace App\Models;

class PostTag extends Model
{
    protected string $table = 'post_tags';
    protected string $primaryKey = 'id';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get tags for a post
     */
    public function getTagsForPost(int $postId): array
    {
        $sql = "SELECT t.* FROM tags t 
                JOIN {$this->table} pt ON t.id = pt.tag_id 
                WHERE pt.post_id = :post_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get posts for a tag
     */
    public function getPostsForTag(int $tagId): array
    {
        $sql = "SELECT p.* FROM posts p 
                JOIN {$this->table} pt ON p.id = pt.post_id 
                WHERE pt.tag_id = :tag_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tag_id' => $tagId]);
        return $stmt->fetchAll();
    }
}