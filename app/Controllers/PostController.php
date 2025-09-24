<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Core\Paginator;
use App\Core\Search;

class PostController extends Controller
{
    protected Post $postModel;
    protected Category $categoryModel;
    protected Tag $tagModel;
    protected Comment $commentModel;
    protected PostCategory $postCategoryModel;
    protected PostTag $postTagModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = new Post();
        $this->categoryModel = new Category();
        $this->tagModel = new Tag();
        $this->commentModel = new Comment();
        $this->postCategoryModel = new PostCategory();
        $this->postTagModel = new PostTag();
    }

    /**
     * Show all posts
     */
    public function index(): string
    {
        $page = (int) $this->input('page', 1);
        $totalPosts = $this->postModel->countWhere(['status' => 'published']);
        $paginator = new Paginator($totalPosts, 10, $page, '/posts');
        
        $posts = $this->postModel->paginate($page, 10);

        return $this->render('post/index', [
            'posts' => $posts,
            'paginator' => $paginator
        ])->getContent();
    }

    /**
     * Show a single post
     */
    public function show(string $slug): string
    {
        $post = $this->postModel->getBySlug($slug);
        
        if (!$post) {
            return $this->response->setStatusCode(404)->setContent('Post not found')->getContent();
        }
        
        $categories = $this->postCategoryModel->getCategoriesForPost($post['id']);
        $tags = $this->postTagModel->getTagsForPost($post['id']);
        $comments = $this->commentModel->getByPost($post['id']);

        return $this->render('post/show', [
            'post' => $post,
            'categories' => $categories,
            'tags' => $tags,
            'comments' => $comments
        ])->getContent();
    }

    /**
     * Search posts
     */
    public function search(): string
    {
        $keyword = $this->input('q', '');
        
        if (empty($keyword)) {
            return $this->redirect('/posts')->getContent();
        }
        
        $search = new Search();
        $search->addModel(Post::class, ['title', 'content', 'excerpt'], ['title' => 2.0, 'content' => 1.0]);
        
        $results = $search->search($keyword, 20);

        return $this->render('post/search', [
            'results' => $results,
            'keyword' => $keyword
        ])->getContent();
    }
}