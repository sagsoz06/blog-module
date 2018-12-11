<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Blog\Entities\Post;
use Modules\Blog\Repositories\CategoryRepository;
use Modules\Blog\Repositories\PostRepository;
use Modules\Core\Http\Controllers\BasePublicController;
use Breadcrumbs;
use Modules\Tag\Repositories\TagRepository;
use Modules\User\Repositories\UserRepository;

class PublicController extends BasePublicController
{
    /**
     * @var PostRepository
     */
    private $post;

    /**
     * @var CategoryRepository
     */
    private $category;

    private $perPage;
    /**
     * @var TagRepository
     */
    private $tag;
    /**
     * @var UserRepository
     */
    private $user;

    /**
     * PublicController constructor.
     * @param PostRepository $post
     * @param CategoryRepository $category
     * @param TagRepository $tag
     * @param UserRepository $user
     */
    public function __construct(
        PostRepository $post,
        CategoryRepository $category,
        TagRepository $tag,
        UserRepository $user
    )
    {
        parent::__construct();
        $this->post = $post;
        $this->category = $category;
        $this->tag = $tag;

        $this->perPage = setting('blog::posts-per-page') == '' ? 5 : setting('blog::posts-per-page');

        /* Start Default Breadcrumbs */
        if(!app()->runningInConsole()) {
            Breadcrumbs::register('blog', function ($breadcrumbs) {
                $breadcrumbs->push(trans('themes::blog.title'), route('blog.index'));
            });
        }
        /* End Default Breadcrumbs */
        $this->user = $user;
    }

    public function index()
    {
        $posts = $this->post->allTranslatedInPaginate($this->locale, $this->perPage);

        /* Start Seo */
        $this->seo()->setTitle(trans('themes::blog.title'))
            ->setDescription(trans('themes::blog.description'))
            ->meta()->setUrl(route('blog.index'))
            ->addMeta('robots', "index, follow")
            ->addAlternates($this->getAlternateLanguages('blog::routes.blog.index'));
        /* End Seo */

        return view('blog::index', compact('posts'));
    }

    public function show($slug)
    {
        $post = $this->post->findBySlug($slug);

        Post::whereId($post->id)->increment('counter');

        $this->throw404IfNotFound($post);

        /* Start Seo */
        $this->seo()->setTitle($post->present()->meta_title)
            ->setDescription($post->present()->meta_description)
            ->setKeywords($post->present()->meta_keywords)
            ->meta()->setUrl($post->url)
            ->addMeta('robots', $post->robots)
            ->addAlternates($post->present()->languages);

        $this->seoGraph()->setTitle($post->present()->og_title)
            ->setType($post->og_type)
            ->setDescription($post->present()->og_description)
            ->setImage($post->present()->og_image)
            ->setUrl($post->url);

        $this->seoCard()->setTitle($post->present()->og_title)
            ->setType('app')
            ->addImage($post->present()->og_image)
            ->setDescription($post->present()->og_description);
        /* End Seo */

        /* Start Breadcrumbs */
        Breadcrumbs::register('blog.show', function ($breadcrumbs) use ($post) {
            $breadcrumbs->parent('blog');
            !isset($post->category->name) ?: $breadcrumbs->push($post->category->name, $post->category->url);
            $breadcrumbs->push($post->title, $post->url);
        });
        /* End Breadcrumbs */

        return view('blog::show', compact('post'));
    }

    public function category($slug)
    {
        $category = $this->category->findBySlug($slug);

        $this->throw404IfNotFound($category);

        $posts = $category->posts()->orderBy('created_at', 'desc')->paginate($this->perPage);

        /* Start Seo */
        $this->seo()->setTitle($category->present()->meta_title)
            ->setDescription($category->present()->meta_description)
            ->meta()->setUrl($category->url)
            ->addMeta('robots', $category->robots)
            ->addAlternates($category->present()->languages);
        /* End Seo */

        /* Start Breadcrumbs */
        Breadcrumbs::register('blog.category', function ($breadcrumbs) use ($category) {
            $breadcrumbs->parent('blog');
            $breadcrumbs->push($category->name, $category->url);
        });
        /* End Breadcrumbs */

        return view('blog::category', compact('category', 'posts'));
    }

    public function author($slug)
    {
        $author = $this->user->findBySlug($slug);

        $this->throw404IfNotFound($author);

        $posts = $this->post->authorPosts($author->id, $this->perPage);

        /* Start Seo */
        $this->seo()->setTitle(trans('themes::blog.author posts', ['author' => $author->fullname]))
            ->setDescription(trans('themes::blog.author posts', ['author' => $author->fullname]))
            ->meta()->setUrl(route('blog.author', [$author->slug]))
            ->addMeta('robots', "index, follow");
        /* End Seo */

        /* Start Breadcrumbs */
        Breadcrumbs::register('blog.author', function ($breadcrumbs) use ($author) {
            $breadcrumbs->parent('blog');
            $breadcrumbs->push(trans('themes::blog.author posts', ['author' => $author->fullname]), route('blog.author', [$author->id]));
        });
        /* End Breadcrumbs */

        return view('blog::author', compact('posts', 'author'));
    }

    public function tagged($slug)
    {
        $tag = $this->tag->findBySlug($slug);

        $posts = $this->post->findByTagPaginate($slug, $this->perPage);

        $this->throw404IfNotFound($posts);

        if (isset($tag)) {
            /* Start Seo */
            $this->seo()->setTitle(trans('blog::post.title.tag', ['tag'=>$tag->name]))
                ->setDescription($tag->name)
                ->meta()->setUrl(route('blog.tag', [$tag->slug]))
                ->addMeta('robots', "index, follow");
            /* End Seo */

            /* Start Breadcrumbs */
            Breadcrumbs::register('blog.tag', function ($breadcrumbs) use ($tag) {
                $breadcrumbs->parent('blog');
                $breadcrumbs->push(trans('blog::post.title.tag', ['tag'=>$tag->name]), route('blog.tag', [$tag->slug]));
            });
            /* End Breadcrumbs */
        }

        return view('blog::tag', compact('posts', 'tag'));
    }

    public function search(Request $request)
    {
        $title = $request->has('s') ? $request->get('s') : trans('themes::theme.search');

        $this->seo()->setTitle($title);

        $posts = $this->post->search($request->get('s'), $this->perPage);

        /* Start Breadcrumbs */
        Breadcrumbs::register('blog.search', function ($breadcrumbs) use ($title) {
            $breadcrumbs->parent('blog');
            $breadcrumbs->push($title);
        });
        /* End Breadcrumbs */

        return view('blog::search', compact('posts', 'title'));
    }

    public function archive($month, $year)
    {
        $posts = $this->post->getArchiveBy($month, $year, $this->perPage);
        if($posts->count()<=0) app()->abort(404);

        $title = trans('blog::post.title.archive', ['month'=>$month, 'year'=>$year]);

        $this->seo()->setTitle($title)
                    ->setDescription($title);

        /* Start Breadcrumbs */
        Breadcrumbs::register('blog.archive', function ($breadcrumbs) use ($title) {
            $breadcrumbs->parent('blog');
            $breadcrumbs->push($title);
        });
        /* End Breadcrumbs */

        return view('blog::archive', compact('posts', 'month', 'year'));
    }

    /**
     * Throw a 404 error page if the given page is not found
     * @param $page
     */
    private function throw404IfNotFound($post)
    {
        if (is_null($post)) {
            app()->abort('404');
        }
    }
}
