<?php

namespace App\Models;

class Post extends Model
{
    protected string $table = 'posts';
    protected string $primaryKey = 'id';
    
    // Define fillable fields for mass assignment
    protected array $fillable = [
        'title',
        'content',
        'excerpt',
        'featured_image',
        'slug',
        'user_id',
        'published_at',
        'status'
    ];
    
    // Define hidden fields that should not be returned in JSON
    protected array $hidden = [];
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get posts by user
     */
    public function getByUser(int $userId): array
    {
        return $this->where(['user_id' => $userId]);
    }
    
    /**
     * Get published posts
     */
    public function getPublished(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'published' AND published_at <= :now ORDER BY published_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['now' => date('Y-m-d H:i:s')]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get posts by slug
     */
    public function getBySlug(string $slug)
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }
    
    /**
     * Search posts by keyword
     */
    public function search(string $keyword): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE (title LIKE :keyword OR content LIKE :keyword) AND status = 'published' ORDER BY published_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['keyword' => "%{$keyword}%"]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get paginated posts
     */
    public function getPaginated(int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM {$this->table} WHERE status = 'published' ORDER BY published_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get post count
     */
    public function getCount(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'published'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }
}