<?php

return [
    'blog'     => [
        'index'  => 'blog',
        'search' => 'blog/ara',
        'slug'   => 'blog/{slug}',
        'tag'    => 'blog/etiket/{tag}',
        'author' => 'blog/yazar/{slug}',
        'archive' => 'blog/arsiv/{month}/{year}'
    ],
    'category' => [
        'slug' => 'blog/kategori/{slug}'
    ]
];