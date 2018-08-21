<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Blog settings
    |--------------------------------------------------------------------------
    */

    'enabled'           => env('BLOG_ENABLED',          true),
    'promoted'          => env('BLOG_PROMOTED_ENABLED', false),
    'show_post_owner'   => env('BLOG_SHOW_POST_OWNER',  true),
];
