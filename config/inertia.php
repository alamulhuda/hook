<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Root View
    |--------------------------------------------------------------------------
    |
    | This is the root view that Inertia will use when rendering pages.
    |
    */
    'root_view' => 'app',

    /*
    |--------------------------------------------------------------------------
    | Page Paths
    |--------------------------------------------------------------------------
    |
    | This is the array of paths to your page components.
    |
    */
    'page_paths' => [
        resource_path('js/pages'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Page Extensions
    |--------------------------------------------------------------------------
    |
    | This is the array of file extensions that will be looked for when
    | resolving page components.
    |
    */
    'page_extensions' => ['vue', 'js', 'ts', 'jsx', 'tsx', 'svelte'],

    /*
    |--------------------------------------------------------------------------
    | SSR
    |--------------------------------------------------------------------------
    |
    | Whether to enable server-side rendering.
    |
    */
    'ssr' => [
        'enabled' => false,
        'url' => 'http://127.0.0.1:13714',
        'ensure_bundle_exists' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing
    |--------------------------------------------------------------------------
    |
    | Configuration for testing.
    |
    */
    'testing' => [
        'ensure_pages_exist' => false,
    ],
];
