<?php namespace Modules\Blog\Presenters;

use Modules\Core\Presenters\BasePresenter;

class CategoryPresenter extends BasePresenter
{
    protected $zone           = 'categoryImage';
    protected $slug           = 'slug';
    protected $transKey       = 'blog::routes.category.slug';
    protected $routeKey       = 'blog.category';
    protected $titleKey       = 'name';
    protected $descriptionKey = 'name';
}