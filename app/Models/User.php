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

    /**
     * Find a user by email
     */
    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Find a user by username
     */
    public function findByUsername(string $username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
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

        return $this->create($data);
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
        
        return $this->update($id, $data);
    }
}