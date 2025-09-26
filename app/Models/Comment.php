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
    protected function cache()
    {
        global $app;
        if (isset($app)) {
            try {
                return $app->getService('cache');
            } catch (\Throwable $e) {
                return new \App\Core\Cache();
            }
        }
        return new \App\Core\Cache();
    }
    
    /**
     * Get comments by post
     */
    public function getByPost(int $postId): array
    {
        $cache = $this->cache();
        $key = 'comments_post_' . $postId;
        $data = $cache->get($key);
        if ($data !== null) {
            return $data;
        }
        $sql = "SELECT * FROM {$this->table} WHERE post_id = :post_id AND status = 'approved' ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['post_id' => $postId]);
        $data = $stmt->fetchAll();
        $cache->put($key, $data, 300);
        return $data;
    }
    
    /**
     * Get comments by user
     */
    public function getByUser(int $userId): array
    {
        return $this->where(['user_id' => $userId]);
    }

    public function create(array $data): int
    {
        $id = parent::create($data);
        $this->cache()->forget('comments_post_' . ($data['post_id'] ?? ''));
        return $id;
    }

    public function update(int $id, array $data): bool
    {
        $res = parent::update($id, $data);
        if (isset($data['post_id'])) {
            $this->cache()->forget('comments_post_' . $data['post_id']);
        }
        return $res;
    }

    public function delete(int $id): bool
    {
        // Attempt to find post_id before deletion to invalidate cache
        $comment = $this->find($id);
        $res = parent::delete($id);
        if ($comment && isset($comment['post_id'])) {
            $this->cache()->forget('comments_post_' . $comment['post_id']);
        }
        return $res;
    }
}