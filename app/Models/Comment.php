<?php

namespace App\Models;

class Comment extends Model
{
    protected string $table = 'comments';
    protected string $primaryKey = 'id';
    
    // Define fillable fields for mass assignment
    protected array $fillable = [
        'post_id',
        'user_id',
        'author_name',
        'author_email',
        'content',
        'status'
    ];
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get comments by post
     */
    public function getByPost(int $postId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE post_id = :post_id AND status = 'approved' ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get comments by user
     */
    public function getByUser(int $userId): array
    {
        return $this->where(['user_id' => $userId]);
    }
}