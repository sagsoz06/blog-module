<?php namespace Modules\Blog\Widgets;

use Modules\Blog\Entities\Post;
use Modules\Blog\Repositories\CategoryRepository;
use Modules\Blog\Repositories\PostRepository;

class BlogWidgets
{
    /**
     * @var PostRepository
     */
    private $post;
    /**
     * @var CategoryRepository
     */
    private $category;

    public function __construct(PostRepository $post, CategoryRepository $category)
    {

        $this->post = $post;
        $this->category = $category;
    }

    public function latest($limit=5, $view='latest')
    {
        $posts = $this->post->latest($limit);
        return view('blog::widgets.'.$view, compact('posts'));
    }

    public function popular($limit=5, $view='popular')
    {
        $posts = $this->post->popular($limit);
        return view('blog::widgets.'.$view, compact('posts'));
    }

    public function categories($limit=10, $view='categories')
    {
        $categories = $this->category->all()->take($limit);
        return view('blog::widgets.'.$view, compact('categories'));
    }

    public function tags(Post $post, $limit=10, $view='tags')
    {
        $tags = $post->tags()->take($limit)->get();
        return view('blog::widgets.'.$view, compact('tags'));
    }
}