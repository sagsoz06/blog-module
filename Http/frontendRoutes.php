<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' =>''], function (Router $router) {
    $router->get(LaravelLocalization::transRoute('blog::routes.blog.index'), [
        'as' => 'blog.index',
        'uses' => 'PublicController@index',
        'middleware' => config('asgard.blog.config.middleware'),
    ]);
    $router->get(LaravelLocalization::transRoute('blog::routes.blog.search'), [
        'as' => 'blog.search',
        'uses' => 'PublicController@search',
        'middleware' => config('asgard.news.config.middleware'),
    ]);
    $router->get(LaravelLocalization::transRoute('blog::routes.blog.slug'), [
        'as' => 'blog.slug',
        'uses' => 'PublicController@show',
        'middleware' => config('asgard.blog.config.middleware'),
    ]);
    $router->get(LaravelLocalization::transRoute('blog::routes.category.slug'), [
        'as' => 'blog.category',
        'uses' => 'PublicController@category',
        'middleware' => config('asgard.blog.config.middleware'),
    ]);
    $router->get(LaravelLocalization::transRoute('blog::routes.blog.tag'), [
        'as' => 'blog.tag',
        'uses' => 'PublicController@tagged',
        'middleware' => config('asgard.blog.config.middleware'),
    ]);
    $router->get(LaravelLocalization::transRoute('blog::routes.blog.author'), [
        'as' => 'blog.author',
        'uses' => 'PublicController@author',
        'middleware' => config('asgard.blog.config.middleware'),
    ]);
});