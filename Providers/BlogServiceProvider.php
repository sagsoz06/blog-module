<?php

namespace Modules\Blog\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes;
use Modules\Blog\Entities\Category;
use Modules\Blog\Entities\Post;
use Modules\Blog\Facades\BlogCategory;
use Modules\Blog\Facades\BlogFacade;
use Modules\Blog\Repositories\Cache\CacheCategoryDecorator;
use Modules\Blog\Repositories\Cache\CachePostDecorator;
use Modules\Blog\Repositories\CategoryRepository;
use Modules\Blog\Repositories\Eloquent\EloquentCategoryRepository;
use Modules\Blog\Repositories\Eloquent\EloquentPostRepository;
use Modules\Blog\Repositories\PostRepository;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Media\Image\ThumbnailManager;
use Modules\Tag\Repositories\TagManager;

class BlogServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->registerFacades();
    }

    public function boot()
    {
        $this->publishConfig('blog', 'config');
        $this->publishConfig('blog', 'permissions');
        $this->publishConfig('blog', 'settings');
        $this->app[TagManager::class]->registerNamespace(new Post());
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        //$this->registerThumbnails();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(PostRepository::class, function () {
            $repository = new EloquentPostRepository(new Post());

            if (config('app.cache') === false) {
                return $repository;
            }

            return new CachePostDecorator($repository);
        });

        $this->app->bind(CategoryRepository::class, function () {
            $repository = new EloquentCategoryRepository(new Category());

            if (config('app.cache') === false) {
                return $repository;
            }

            return new CacheCategoryDecorator($repository);
        });
    }

    private function registerThumbnails()
    {
        $this->app[ThumbnailManager::class]->registerThumbnail('smallThumb', [
            'fit' => [
                'width' => '150',
                'height' => '150',
                'callback' => function ($constraint) {
                    $constraint->upsize();
                },
            ],
        ]);
    }

    private function registerFacades()
    {
        $aliasLoader = AliasLoader::getInstance();
        $aliasLoader->alias('Blog', BlogFacade::class);
        $aliasLoader->alias('BlogCategory', BlogCategory::class);
    }
}