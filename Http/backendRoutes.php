<?php

use Illuminate\Routing\Router;

/** @var Router $router */


$router->group(['prefix' => '/blog'], function (Router $router) {

    $router->bind('blogPost', function ($id) {
        return app(\Modules\Blog\Repositories\PostRepository::class)->find($id);
    });

    $router->get('posts', [
        'as' => 'admin.blog.post.index',
        'uses' => 'PostController@index',
        'middleware' => 'can:blog.posts.index',
    ]);
    $router->get('posts/create', [
        'as' => 'admin.blog.post.create',
        'uses' => 'PostController@create',
        'middleware' => 'can:blog.posts.create',
    ]);
    $router->post('posts', [
        'as' => 'admin.blog.post.store',
        'uses' => 'PostController@store',
        'middleware' => 'can:blog.posts.create',
    ]);
    $router->get('posts/{blogPost}/edit', [
        'as' => 'admin.blog.post.edit',
        'uses' => 'PostController@edit',
        'middleware' => 'can:blog.posts.edit',
    ]);
    $router->put('posts/{blogPost}', [
        'as' => 'admin.blog.post.update',
        'uses' => 'PostController@update',
        'middleware' => 'can:blog.posts.edit',
    ]);
    $router->delete('posts/{blogPost}', [
        'as' => 'admin.blog.post.destroy',
        'uses' => 'PostController@destroy',
        'middleware' => 'can:blog.posts.destroy',
    ]);

    $router->bind('blogCategory', function ($id) {
        return app(\Modules\Blog\Repositories\CategoryRepository::class)->find($id);
    });

    $router->get('categories', [
        'as' => 'admin.blog.category.index',
        'uses' => 'CategoryController@index',
        'middleware' => 'can:blog.categories.index',
    ]);
    $router->get('categories/create', [
        'as' => 'admin.blog.category.create',
        'uses' => 'CategoryController@create',
        'middleware' => 'can:blog.categories.create',
    ]);
    $router->post('categories', [
        'as' => 'admin.blog.category.store',
        'uses' => 'CategoryController@store',
        'middleware' => 'can:blog.categories.create',
    ]);
    $router->get('categories/{blogCategory}/edit', [
        'as' => 'admin.blog.category.edit',
        'uses' => 'CategoryController@edit',
        'middleware' => 'can:blog.categories.edit',
    ]);
    $router->put('categories/{blogCategory}', [
        'as' => 'admin.blog.category.update',
        'uses' => 'CategoryController@update',
        'middleware' => 'can:blog.categories.edit',
    ]);
    $router->delete('categories/{blogCategory}', [
        'as' => 'admin.blog.category.destroy',
        'uses' => 'CategoryController@destroy',
        'middleware' => 'can:blog.categories.destroy',
    ]);

});
