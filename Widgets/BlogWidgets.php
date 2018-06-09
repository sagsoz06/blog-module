<?php namespace Modules\Blog\Widgets;

use Illuminate\Pagination\LengthAwarePaginator;
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

    /**
     * BlogWidgets constructor.
     * @param PostRepository $post
     * @param CategoryRepository $category
     */
    public function __construct(PostRepository $post, CategoryRepository $category)
    {

        $this->post = $post;
        $this->category = $category;
    }

    /**
     * @param int $limit
     * @param string $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function latest($limit=5, $view='latest')
    {
        $posts = $this->post->latest($limit);
        return view('blog::widgets.'.$view, compact('posts'));
    }

    /**
     * @param int $limit
     * @param string $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function popular($limit=5, $view='popular')
    {
        $posts = $this->post->popular($limit);
        return view('blog::widgets.'.$view, compact('posts'));
    }

    /**
     * @param int $limit
     * @param string $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function categories($limit=10, $view='categories')
    {
        $categories = $this->category->all()->take($limit);
        return view('blog::widgets.'.$view, compact('categories'));
    }

    /**
     * @param Post $posts
     * @param int $limit
     * @param string $view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tags($posts, $limit=10, $view='tags')
    {
        if($posts instanceof LengthAwarePaginator) {
            $tags = $posts->filter(function($post){
                return $post->tags->count() > 0;
            })->map(function($post) use ($limit) {
                return $post->tags()->take($limit)->get();
            });
            $tags = $tags->flatten();
        } else {
            $tags = $posts->tags()->take($limit)->get();
        }
        return view('blog::widgets.'.$view, compact('tags'));
    }
}