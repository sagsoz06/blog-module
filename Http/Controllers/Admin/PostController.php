<?php

namespace Modules\Blog\Http\Controllers\Admin;

use Modules\Blog\Entities\Helpers\OgType;
use Modules\Blog\Entities\Post;
use Modules\Blog\Entities\Status;
use Modules\Blog\Http\Requests\CreatePostRequest;
use Modules\Blog\Http\Requests\UpdatePostRequest;
use Modules\Blog\Repositories\CategoryRepository;
use Modules\Blog\Repositories\PostRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Media\Repositories\FileRepository;
use Datatables;
use Modules\User\Contracts\Authentication;
use Modules\User\Repositories\UserRepository;

class PostController extends AdminBaseController
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
     * @var FileRepository
     */
    private $file;
    /**
     * @var Status
     */
    private $status;

    private $ogType;
    /**
     * @var UserRepository
     */
    private $user;
    /**
     * @var Authentication
     */
    private $auth;

    public function __construct(
        PostRepository $post,
        CategoryRepository $category,
        FileRepository $file,
        Status $status,
        OgType $ogType,
        UserRepository $user,
        Authentication $auth
    ) {
        parent::__construct();

        $this->post = $post;
        $this->category = $category;
        $this->file = $file;
        $this->status = $status;
        $this->ogType = $ogType;
        $this->user = $user;
        $this->auth = $auth;

        view()->share('ogTypes', $this->ogType->lists());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $posts = Post::query();
        if($this->auth->user()->inRole('admin')) {
            $posts = $posts->where('user_id', $this->auth->user()->id);
        }
        if(request()->ajax()) {
            return Datatables::of($posts)
                ->addColumn('status', function($post){
                    return '<span class="label '.$post->present()->statusLabelClass.'">'.
                    $post->present()->status
                    .'</span>';
                })
                ->editColumn('category.name', function ($post) {
                    return isset($post->category->name) ? $post->category->name : '';
                })
                ->addColumn('action', function ($post) {
                    $action_buttons =   \Html::decode(link_to(
                        route('admin.blog.post.edit',
                            [$post->id]),
                        '<i class="fa fa-pencil"></i>',
                        ['class'=>'btn btn-default btn-flat']
                    ));
                    $action_buttons .=  \Html::decode(\Form::button(
                        '<i class="fa fa-trash"></i>',
                        ["data-toggle" => "modal",
                         "data-action-target" => route("admin.blog.post.destroy", [$post->id]),
                         "data-target" => "#modal-delete-confirmation",
                         "class"=>"btn btn-danger btn-flat"]
                    ));
                    return $action_buttons;
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('blog::admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = $this->category->allTranslatedIn(app()->getLocale());
        $statuses = $this->status->lists();
        $this->assetPipeline->requireJs('ckeditor.js');

        return view('blog::admin.posts.create', compact('categories', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreatePostRequest $request)
    {
        $this->post->create($request->all());

        return redirect()->route('admin.blog.post.index')
            ->withSuccess(trans('blog::messages.post created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     * @return \Illuminate\View\View
     */
    public function edit(Post $post)
    {
        $categories = $this->category->allTranslatedIn(app()->getLocale());
        $statuses = $this->status->lists();
        $this->assetPipeline->requireJs('ckeditor.js');

        return view('blog::admin.posts.edit', compact('post', 'categories', 'thumbnail', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Post $post
     * @param UpdatePostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Post $post, UpdatePostRequest $request)
    {
        $this->post->update($post, $request->all());

        if($this->auth->hasAccess('blog.posts.author')) {
            if($request->has('user_id')) {
                $user = $this->user->find($request->user_id);
                $post->author()->associate($user);
                $post->save();
            }
        }

        return redirect()->route('admin.blog.post.index')
            ->withSuccess(trans('blog::messages.post updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post)
    {
        $post->tags()->detach();

        $this->post->destroy($post);

        return redirect()->route('admin.blog.post.index')
            ->withSuccess(trans('blog::messages.post deleted'));
    }
}
