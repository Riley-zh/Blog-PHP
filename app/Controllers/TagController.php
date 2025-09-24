<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Tag;
use App\Models\Post;
use App\Models\PostTag;

class TagController extends Controller
{
    protected Tag $tagModel;
    protected Post $postModel;
    protected PostTag $postTagModel;

    public function __construct()
    {
        parent::__construct();
        $this->tagModel = new Tag();
        $this->postModel = new Post();
        $this->postTagModel = new PostTag();
    }

    /**
     * Show all tags
     */
    public function index(): string
    {
        $tags = $this->tagModel->getAllWithPostCount();

        return $this->render('tag/index', [
            'tags' => $tags
        ])->getContent();
    }

    /**
     * Show posts with a tag
     */
    public function show(string $slug): string
    {
        $tag = $this->tagModel->getBySlug($slug);
        
        if (!$tag) {
            return $this->response->setStatusCode(404)->setContent('Tag not found')->getContent();
        }
        
        $posts = $this->postTagModel->getPostsForTag($tag['id']);

        return $this->render('tag/show', [
            'tag' => $tag,
            'posts' => $posts
        ])->getContent();
    }
}