<?php namespace Modules\Blog\Widgets;

use Modules\Blog\Repositories\PostRepository;

class BlogWidgets
{
    /**
     * @var PostRepository
     */
    private $post;

    public function __construct(PostRepository $post)
    {

        $this->post = $post;
    }

    public function latestPosts($limit=5, $view='latestPosts')
    {
        if($posts = $this->post->latest($limit))
        {
            return view('blog::widgets.'.$view, compact('posts'))->render();
        }
        return false;
    }
}