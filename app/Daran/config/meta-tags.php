<?php

return array(

    /*
     |--------------------------------------------------------------------------
     | Site default title
     |--------------------------------------------------------------------------
     |
     */

    'title' => 'Inaction',

    /*
     |--------------------------------------------------------------------------
     | Limit title meta tag length
     |--------------------------------------------------------------------------
     |
     | To best SEO implementation, limit tags.
     |
     */

    'title_limit' => 70,

    /*
     |--------------------------------------------------------------------------
     | Limit description meta tag length
     |--------------------------------------------------------------------------
     |
     | To best SEO implementation, limit tags.
     |
     */

    'description_limit' => 200,

    /*
     |--------------------------------------------------------------------------
     | OpenGraph values
     |--------------------------------------------------------------------------
     |
     */

    'open_graph' => [
        'site_name' => 'Inaction',
        'type' => 'website'
    ],

    /*
     |--------------------------------------------------------------------------
     | Twitter Card values
     |--------------------------------------------------------------------------
     |
     */

    'twitter' => [
        'card' => 'summary',
        'creator' => '@mysite',
        'site' => '@mysite'
    ],

    /*
     |--------------------------------------------------------------------------
     | Supported languages
     |--------------------------------------------------------------------------
     |
     */

    'locale_url' => '[scheme]://[locale][host][uri]',

    /*
     |--------------------------------------------------------------------------
     | Supported languages
     |--------------------------------------------------------------------------
     |
     */

    'locales' => ['en', 'it'],
);
