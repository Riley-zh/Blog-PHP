<?php

namespace App\Models;

class User extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';
    
    // Define fillable fields for mass assignment
    protected array $fillable = [
        'username',
        'email',
        'password'
    ];
    
    // Define hidden fields that should not be returned in JSON
    protected array $hidden = [
        'password'
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
     * Find a user by email
     */
    public function findByEmail(string $email)
    {
        $cache = $this->cache();
        $key = 'user_email_' . md5($email);
        $data = $cache->get($key);
        if ($data !== null) {
            return $data;
        }
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();
        if ($data) {
            $cache->put($key, $data, 600);
        }
        return $data;
    }

    /**
     * Find a user by username
     */
    public function findByUsername(string $username)
    {
        $cache = $this->cache();
        $key = 'user_username_' . md5($username);
        $data = $cache->get($key);
        if ($data !== null) {
            return $data;
        }
        $sql = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        $data = $stmt->fetch();
        if ($data) {
            $cache->put($key, $data, 600);
        }
        return $data;
    }

    /**
     * Create a new user
     */
    public function createUser(array $data): int
    {
        // Hash the password before saving
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $id = $this->create($data);
        // invalidate any relevant cache
        $this->cache()->clear();
        return $id;
    }

    /**
     * Verify user password
     */
    public function verifyPassword(string $email, string $password): bool
    {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        return password_verify($password, $user['password']);
    }
    
    /**
     * Update user information
     */
    public function updateUser(int $id, array $data): bool
    {
        // Hash the password if it's being updated
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $res = $this->update($id, $data);
        $this->cache()->clear();
        return $res;
    }

    public function deleteUser(int $id): bool
    {
        $res = $this->delete($id);
        $this->cache()->clear();
        return $res;
    }
}