<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    /**
     * Show the home page
     */
    public function index(): string
    {
        return $this->render('home', [
            'title' => 'Welcome to Modern PHP Blog',
            'message' => 'This is a high-performance PHP blog CMS'
        ])->getContent();
    }

    /**
     * Show the about page
     */
    public function about(): string
    {
        return $this->render('about', [
            'title' => 'About Us',
            'content' => 'This is a modern PHP blog CMS built with performance and scalability in mind.'
        ])->getContent();
    }
}