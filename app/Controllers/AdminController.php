<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Core\FileUploader;
use App\Core\Response;

class AdminController extends Controller
{
    protected Post $postModel;
    protected Category $categoryModel;
    protected Tag $tagModel;
    protected User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = new Post();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
        $this->userModel = new User();
    }

    /**
     * Show admin dashboard
     */
    public function dashboard(): string
    {
        $postCount = $this->postModel->count();
        $categoryCount = $this->categoryModel->count();
        $tagCount = $this->tagModel->count();
        $userCount = $this->userModel->count();

        return $this->render('admin/dashboard', [
            'postCount' => $postCount,
            'categoryCount' => $categoryCount,
            'tagCount' => $tagCount,
            'userCount' => $userCount
        ])->getContent();
    }

    /**
     * Show posts management
     */
    public function posts(): string
    {
        $posts = $this->postModel->all();

        return $this->render('admin/posts/index', [
            'posts' => $posts
        ])->getContent();
    }

    /**
     * Show create post form
     */
    public function createPost(): string
    {
        return $this->render('admin/posts/create', $this->getCategoryAndTagData())->getContent();
    }

    /**
     * Store a new post
     */
    public function storePost(): string
    {
        $title = $this->input('title');
        $content = $this->input('content');
        $excerpt = $this->input('excerpt');
        $categoryId = $this->input('category_id');
        $tagIds = $this->input('tag_ids', []);

        // Validate input
        if (empty($title) || empty($content)) {
            return $this->renderWithError('admin/posts/create', 'Title and content are required', $this->getCategoryAndTagData())->getContent();
        }

        // Handle featured image upload
        $featuredImage = null;
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $uploader = new FileUploader(dirname(__DIR__, 2) . '/storage/uploads/posts');
            $uploader->setAllowedTypes(['jpg', 'jpeg', 'png', 'gif'])
                     ->setMaxFileSize(2 * 1024 * 1024); // 2MB
            
            $filename = $uploader->upload($_FILES['featured_image']);
            
            if ($filename) {
                $featuredImage = '/uploads/posts/' . $filename;
            } elseif ($uploader->hasErrors()) {
                return $this->renderWithError('admin/posts/create', 'Failed to upload featured image: ' . implode(', ', $uploader->getErrors()), $this->getCategoryAndTagData())->getContent();
            }
        }

        // Create post
        $postId = $this->postModel->create([
            'title' => $title,
            'content' => $content,
            'excerpt' => $excerpt,
            'slug' => $this->createSlug($title),
            'user_id' => 1, // For now, hardcode user ID
            'published_at' => date('Y-m-d H:i:s'),
            'status' => 'published',
            'featured_image' => $featuredImage
        ]);

        if ($postId) {
            // Redirect to edit post page
            return $this->redirect("/admin/posts/{$postId}/edit")->getContent();
        }

        return $this->renderWithError('admin/posts/create', 'Failed to create post', $this->getCategoryAndTagData())->getContent();
    }

    /**
     * Show edit post form
     */
    public function editPost(int $id): string
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return $this->response->setStatusCode(404)->setContent('Post not found')->getContent();
        }

        return $this->render('admin/posts/edit', array_merge([
            'post' => $post
        ], $this->getCategoryAndTagData()))->getContent();
    }

    /**
     * Update a post
     */
    public function updatePost(int $id): string
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return $this->response->setStatusCode(404)->setContent('Post not found')->getContent();
        }

        $title = $this->input('title');
        $content = $this->input('content');
        $excerpt = $this->input('excerpt');

        // Validate input
        if (empty($title) || empty($content)) {
            return $this->renderWithError('admin/posts/edit', 'Title and content are required', array_merge(['post' => $post], $this->getCategoryAndTagData()))->getContent();
        }

        // Handle featured image upload
        $featuredImage = $post['featured_image'];
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $uploader = new FileUploader(dirname(__DIR__, 2) . '/storage/uploads/posts');
            $uploader->setAllowedTypes(['jpg', 'jpeg', 'png', 'gif'])
                     ->setMaxFileSize(2 * 1024 * 1024); // 2MB
            
            $filename = $uploader->upload($_FILES['featured_image']);
            
            if ($filename) {
                $featuredImage = '/uploads/posts/' . $filename;
            } elseif ($uploader->hasErrors()) {
                return $this->renderWithError('admin/posts/edit', 'Failed to upload featured image: ' . implode(', ', $uploader->getErrors()), array_merge(['post' => $post], $this->getCategoryAndTagData()))->getContent();
            }
        }

        // Update post
        $updated = $this->postModel->update($id, [
            'title' => $title,
            'content' => $content,
            'excerpt' => $excerpt,
            'slug' => $this->createSlug($title),
            'featured_image' => $featuredImage
        ]);

        if ($updated) {
            return $this->redirect("/admin/posts/{$id}/edit")->getContent();
        }

        return $this->renderWithError('admin/posts/edit', 'Failed to update post', array_merge(['post' => $post], $this->getCategoryAndTagData()))->getContent();
    }

    /**
     * Delete a post
     */
    public function deletePost(int $id): string
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return $this->response->setStatusCode(404)->setContent('Post not found')->getContent();
        }

        $deleted = $this->postModel->delete($id);

        if ($deleted) {
            return $this->redirect('/admin/posts')->getContent();
        }

        return $this->render('admin/posts/index', [
            'error' => 'Failed to delete post',
            'posts' => $this->postModel->all()
        ])->getContent();
    }

    /**
     * Get category and tag data for forms
     */
    private function getCategoryAndTagData(): array
    {
        return [
            'categories' => $this->categoryModel->all(),
            'tags' => $this->tagModel->all()
        ];
    }

    /**
     * Create a URL-friendly slug
     */
    private function createSlug(string $text): string
    {
        // Replace non-letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // Trim
        $text = trim($text, '-');

        // Remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // Lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * Render view with error message
     */
    private function renderWithError(string $view, string $errorMessage, array $data = []): Response
    {
        return $this->render($view, array_merge(['error' => $errorMessage], $data));
    }
}