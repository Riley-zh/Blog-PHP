<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostCategory;

class CategoryController extends Controller
{
    protected Category $categoryModel;
    protected Post $postModel;
    protected PostCategory $postCategoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = new Category();
        $this->postModel = new Post();
        $this->postCategoryModel = new PostCategory();
    }

    /**
     * Show all categories
     */
    public function index(): string
    {
        $categories = $this->categoryModel->getAllWithPostCount();

        return $this->render('category/index', [
            'categories' => $categories
        ])->getContent();
    }

    /**
     * Show posts in a category
     */
    public function show(string $slug): string
    {
        $category = $this->categoryModel->getBySlug($slug);
        
        if (!$category) {
            return $this->response->setStatusCode(404)->setContent('Category not found')->getContent();
        }
        
        $posts = $this->postCategoryModel->getPostsForCategory($category['id']);

        return $this->render('category/show', [
            'category' => $category,
            'posts' => $posts
        ])->getContent();
    }
}