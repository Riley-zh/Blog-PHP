<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    protected User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    /**
     * Show the login form
     */
    public function showLoginForm(): string
    {
        return $this->render('auth/login')->getContent();
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
            return $this->render('auth/login', [
                'error' => 'Please fill in all fields'
            ])->getContent();
        }

        // Verify credentials
        if ($this->userModel->verifyPassword($email, $password)) {
            // Set session or token for authentication
            // For simplicity, we'll just redirect to home
            return $this->redirect('/')->getContent();
        }

        return $this->render('auth/login', [
            'error' => 'Invalid credentials'
        ])->getContent();
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm(): string
    {
        return $this->render('auth/register')->getContent();
    }

    /**
     * Handle registration request
     */
    public function register(): string
    {
        $username = $this->input('username');
        $email = $this->input('email');
        $password = $this->input('password');
        $confirmPassword = $this->input('confirm_password');

        // Validate input
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            return $this->render('auth/register', [
                'error' => 'Please fill in all fields'
            ])->getContent();
        }

        if ($password !== $confirmPassword) {
            return $this->render('auth/register', [
                'error' => 'Passwords do not match'
            ])->getContent();
        }

        // Check if user already exists
        if ($this->userModel->findByEmail($email)) {
            return $this->render('auth/register', [
                'error' => 'Email already registered'
            ])->getContent();
        }

        if ($this->userModel->findByUsername($username)) {
            return $this->render('auth/register', [
                'error' => 'Username already taken'
            ])->getContent();
        }

        // Create user
        $userId = $this->userModel->createUser([
            'username' => $username,
            'email' => $email,
            'password' => $password
        ]);

        if ($userId) {
            // Redirect to login page
            return $this->redirect('/login')->getContent();
        }

        return $this->render('auth/register', [
            'error' => 'Failed to create account'
        ])->getContent();
    }

    /**
     * Handle logout
     */
    public function logout(): string
    {
        // Destroy session or token
        // For simplicity, we'll just redirect to home
        return $this->redirect('/')->getContent();
    }

    /**
     * Show user profile
     */
    public function profile(): string
    {
        // In a real application, you would fetch user data from database
        // For now, we'll just show a simple profile page
        return $this->render('auth/profile', [
            'user' => [
                'username' => 'John Doe',
                'email' => 'john@example.com'
            ]
        ])->getContent();
    }
}