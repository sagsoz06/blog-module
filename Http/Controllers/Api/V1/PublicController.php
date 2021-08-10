<?php

namespace Modules\Blog\Http\Controllers\Api\V1;

use Illuminate\Http\Response;
use Modules\Blog\Repositories\CategoryRepository;
use Modules\Blog\Repositories\PostRepository;
use Modules\Core\Http\Controllers\BasePublicController;
use Modules\Tag\Repositories\TagRepository;

class PublicController extends BasePublicController
{
    /**
     * @var PostRepository
     */
    protected $post;

    protected $perPage;
    /**
     * @var TagRepository
     */
    private $tag;
    /**
     * @var CategoryRepository
     */
    private $category;

    public function __construct(PostRepository $post, TagRepository $tag, CategoryRepository $category)
    {
        parent::__construct();
        $this->post = $post;

        $this->perPage = setting('blog::posts-per-page') == '' ? 5 : setting('blog::posts-per-page');
        $this->tag = $tag;
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if(request()->ajax()) {
            $page = \Request::has('page') ? \Request::query('page') : 1;
            $posts = $this->post->paginate($this->perPage);
            $results = collect();
            foreach ($posts as $post) {
                $results->push([
                    'title'      => $post->title,
                    'url'        => $post->url,
                    'image'      => $post->present()->firstImage(1170, 600, 'fit', 80),
                    'created_at' => $post->created_at->formatLocalized('%d.%M.%Y'),
                    'intro'      => $post->intro,
                    'author'     => [
                        'name' => $post->author->fullname
                    ],
                    'category' => [
                        'name' => $post->category->name,
                        'url'  => $post->category->url
                    ]
                ]);
            }
            return response()->json([
                'success' => true,
                'posts'   => $results,
                'page'    => $page
            ], Response::HTTP_OK);
        } else {
            return response()->json([
               'success' => false,
               'message' => 'Hatalı istek'
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function category()
    {
        $category = $this->category->findBySlug(\Request::get('category'));

        if(request()->ajax() && $category) {
            $page = \Request::has('page') ? \Request::query('page') : 1;
            $posts = $category->posts()->orderBy('created_at', 'desc')->paginate($this->perPage);
            $results = collect();
            foreach ($posts as $post) {
                $results->push([
                    'title'      => $post->title,
                    'url'        => $post->url,
                    'image'      => $post->present()->firstImage(1170, 600, 'fit', 80),
                    'created_at' => $post->created_at->formatLocalized('%d.%M.%Y'),
                    'intro'      => $post->intro,
                    'author'     => [
                        'name' => $post->author->fullname
                    ],
                    'category' => [
                        'name' => $post->category->name,
                        'url'  => $post->category->url
                    ]
                ]);
            }
            return response()->json([
                'success' => true,
                'posts'   => $results,
                'page'    => $page
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hatalı istek'
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    public function tagged()
    {
        $tag = $this->tag->findBySlug(\Request::get('tag'));

        if(request()->ajax() && $tag) {

            $page = \Request::has('page') ? \Request::query('page') : 1;
            $posts = $this->post->findByTagPaginate($tag->slug, $this->perPage);
            $results = collect();
            foreach ($posts as $post) {
                $results->push([
                    'title'      => $post->title,
                    'url'        => $post->url,
                    'image'      => $post->present()->firstImage(1170, 600, 'fit', 80),
                    'created_at' => $post->created_at->formatLocalized('%d.%M.%Y'),
                    'intro'      => $post->intro,
                    'author'     => [
                        'name' => $post->author->fullname
                    ],
                    'category' => [
                        'name' => $post->category->name,
                        'url'  => $post->category->url
                    ]
                ]);
            }
            return response()->json([
                'success' => true,
                'posts'   => $results,
                'page'    => $page
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hatalı istek'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
