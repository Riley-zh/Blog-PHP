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
     * Get tag by slug
     */
    public function getBySlug(string $slug)
    {
        $cache = $this->cache();
        $key = 'tag_slug_' . md5($slug);
        $data = $cache->get($key);
        if ($data !== null) {
            return $data;
        }
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $data = $stmt->fetch();
        if ($data) {
            $cache->put($key, $data, 600);
        }
        return $data;
    }
    
    /**
     * Get all tags with post counts
     */
    public function getAllWithPostCount(): array
    {
        $cache = $this->cache();
        $key = 'tags_with_count';
        $data = $cache->get($key);
        if ($data !== null) {
            return $data;
        }
        $sql = "SELECT t.*, COUNT(pt.post_id) as post_count 
                FROM {$this->table} t 
                LEFT JOIN post_tags pt ON t.id = pt.tag_id 
                GROUP BY t.id 
                ORDER BY t.name";
        $stmt = $this->db->query($sql);
        $data = $stmt->fetchAll();
        $cache->put($key, $data, 600);
        return $data;
    }

    public function create(array $data): int
    {
        $id = parent::create($data);
        $this->cache()->forget('tags_with_count');
        return $id;
    }

    public function update(int $id, array $data): bool
    {
        $res = parent::update($id, $data);
        $this->cache()->forget('tags_with_count');
        return $res;
    }

    public function delete(int $id): bool
    {
        $res = parent::delete($id);
        $this->cache()->forget('tags_with_count');
        return $res;
    }
}