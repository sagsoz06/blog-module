<?php

return [
    'blog'     => [
        'index'  => 'blog',
        'search' => 'blog/search',
        'slug'   => 'blog/{slug}',
        'tag'    => 'blog/tag/{tag}',
        'author' => 'blog/author/{slug}'
    ],
    'category' => [
        'slug' => 'blog/category/{slug}'
    ]
];