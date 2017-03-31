<?php namespace Modules\Blog\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Blog\Repositories\CategoryRepository;

class BlogCategory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CategoryRepository::class;
    }
}