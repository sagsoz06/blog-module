<?php namespace Modules\Blog\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Blog\Repositories\PostRepository;

class BlogFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PostRepository::class;
    }
}