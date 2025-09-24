<?php

namespace App\Controllers\Api;

use App\Models\User;

class AuthController extends ApiController
{
    protected User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    /**
     * Handle login request
     */
    public function login(): string
    {
        $email = $this->input('email');
        $password = $this->input('password');

        // Validate input
        if (empty($email) || empty($password)) {
            return $this->validationError([
                'email' => 'Email is required',
                'password' => 'Password is required'
            ])->getContent();
        }

        // Verify credentials
        if ($this->userModel->verifyPassword($email, $password)) {
            // In a real application, you would generate a JWT token or similar
            $token = bin2hex(random_bytes(32));
            
            return $this->success('Login successful', [
                'token' => $token,
                'user' => [
                    'email' => $email
                ]
            ])->getContent();
        }

        return $this->error('Invalid credentials')->getContent();
    }

    /**
     * Handle registration request
     */
    public function register(): string
    {
        $username = $this->input('username');
        $email = $this->input('email');
        $password = $this->input('password');

        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            return $this->validationError([
                'username' => 'Username is required',
                'email' => 'Email is required',
                'password' => 'Password is required'
            ])->getContent();
        }

        // Check if user already exists
        if ($this->userModel->findByEmail($email)) {
            return $this->error('Email already registered')->getContent();
        }

        if ($this->userModel->findByUsername($username)) {
            return $this->error('Username already taken')->getContent();
        }

        // Create user
        $userId = $this->userModel->createUser([
            'username' => $username,
            'email' => $email,
            'password' => $password
        ]);

        if ($userId) {
            // In a real application, you would generate a JWT token or similar
            $token = bin2hex(random_bytes(32));
            
            return $this->success('Registration successful', [
                'token' => $token,
                'user' => [
                    'username' => $username,
                    'email' => $email
                ]
            ], 201)->getContent();
        }

        return $this->error('Failed to create account')->getContent();
    }
}