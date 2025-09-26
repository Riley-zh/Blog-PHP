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
     * Get category by slug
     */
    public function getBySlug(string $slug)
    {
        $cache = $this->cache();
        $key = 'category_slug_' . md5($slug);
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
     * Get all categories with post counts
     */
    public function getAllWithPostCount(): array
    {
        $cache = $this->cache();
        $key = 'categories_with_count';
        $data = $cache->get($key);
        if ($data !== null) {
            return $data;
        }
        $sql = "SELECT c.*, COUNT(pc.post_id) as post_count 
                FROM {$this->table} c 
                LEFT JOIN post_categories pc ON c.id = pc.category_id 
                GROUP BY c.id 
                ORDER BY c.name";
        $stmt = $this->db->query($sql);
        $data = $stmt->fetchAll();
        $cache->put($key, $data, 600);
        return $data;
    }

    public function create(array $data): int
    {
        $id = parent::create($data);
        $this->cache()->forget('categories_with_count');
        return $id;
    }

    public function update(int $id, array $data): bool
    {
        $res = parent::update($id, $data);
        $this->cache()->forget('categories_with_count');
        return $res;
    }

    public function delete(int $id): bool
    {
        $res = parent::delete($id);
        $this->cache()->forget('categories_with_count');
        return $res;
    }
}