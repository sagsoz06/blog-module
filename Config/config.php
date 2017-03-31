<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Define an array of views on which to bind the $latestPosts collection
    |--------------------------------------------------------------------------
    */
    'latest-posts' => [
        'blog.*',
    ],

    'post' => [
        /*
        |--------------------------------------------------------------------------
        | Partials to include on page views
        |--------------------------------------------------------------------------
        | List the partials you wish to include on the different type page views
        | The content of those fields well be caught by the PostWasCreated and PostWasEdited events
        */
        'partials' => [
            'translatable' => [
                'create' => [],
                'edit' => [],
            ],
            'normal' => [
                'create' => [],
                'edit' => [],
            ],
        ],
        /*
        |--------------------------------------------------------------------------
        | Dynamic relations
        |--------------------------------------------------------------------------
        | Add relations that will be dynamically added to the Post entity
        */
        'relations' => [
            //        'extension' => function ($self) {
            //            return $self->belongsTo(PostExtension::class, 'id', 'post_id')->first();
            //        }
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Array of middleware that will be applied on the page module front end routes
    |--------------------------------------------------------------------------
    */
    'middleware' => [],

    /*
    |--------------------------------------------------------------------------
    | Load additional view namespaces for a module
    |--------------------------------------------------------------------------
    | You can specify place from which you would like to use module views.
    | You can use any combination, but generally it's advisable to add only one,
    | extra view namespace.
    | By default every extra namespace will be set to false.
    */
    'useViewNamespaces' => [
        // Read module views from /Themes/<backend-theme-name>/views/modules/<module-name>
        'backend-theme' => false,
        // Read module views from /Themes/<frontend-theme-name>/views/modules/<module-name>
        'frontend-theme' => true,
        // Read module views from /resources/views/<module-name>
        'resources' => true,
    ],
];