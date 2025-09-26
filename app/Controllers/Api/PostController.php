<?php

namespace App\Controllers\Api;

use App\Models\Post;

class PostController extends ApiController
{
    protected Post $postModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = new Post();
    }

    /**
     * Get all posts
     */
    public function index(): string
    {
    $posts = $this->postModel->all();
        return $this->success('Posts retrieved successfully', $posts)->getContent();
    }

    /**
     * Get a specific post
     */
    public function show(int $id): string
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return $this->notFound('Post not found')->getContent();
        }
        
        return $this->success('Post retrieved successfully', $post)->getContent();
    }

    /**
     * Create a new post
     */
    public function store(): string
    {
        $title = $this->input('title');
        $content = $this->input('content');
        
        // Validation
        if (empty($title) || empty($content)) {
            return $this->validationError([
                'title' => 'Title is required',
                'content' => 'Content is required'
            ])->getContent();
        }
        
        $postData = [
            'title' => $title,
            'content' => $content,
            'user_id' => 1 // In a real app, this would come from auth
        ];
        
        $postId = $this->postModel->create($postData);
        
        if ($postId) {
            $post = $this->postModel->find($postId);
            return $this->success('Post created successfully', $post, 201)->getContent();
        }
        
        return $this->error('Failed to create post')->getContent();
    }

    /**
     * Update a post
     */
    public function update(int $id): string
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return $this->notFound('Post not found')->getContent();
        }
        
        $title = $this->input('title');
        $content = $this->input('content');
        
        // Validation
        if (empty($title) || empty($content)) {
            return $this->validationError([
                'title' => 'Title is required',
                'content' => 'Content is required'
            ])->getContent();
        }
        
        $updated = $this->postModel->update($id, [
            'title' => $title,
            'content' => $content
        ]);
        
        if ($updated) {
            $updatedPost = $this->postModel->find($id);
            return $this->success('Post updated successfully', $updatedPost)->getContent();
        }
        
        return $this->error('Failed to update post')->getContent();
    }

    /**
     * Delete a post
     */
    public function destroy(int $id): string
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return $this->notFound('Post not found')->getContent();
        }
        
        $deleted = $this->postModel->delete($id);
        
        if ($deleted) {
            return $this->success('Post deleted successfully')->getContent();
        }
        
        return $this->error('Failed to delete post')->getContent();
    }
}