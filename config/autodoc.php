<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Autodoc Master Switch
    |--------------------------------------------------------------------------
    |
    | Turning write documentation on and off when starting tests.
    | For example, turn it off during CI tests
    */

    'enabled' => env('AUTODOC_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Assets file Path
    |--------------------------------------------------------------------------
    |
    | Path to documentation swagger styles
    */

    'path_dir_static' => [
        'swagger' => env('AUTODOC_PROD_PATH', asset('/')),
    ],

    /*
    |--------------------------------------------------------------------------
    | All API your project
    |--------------------------------------------------------------------------
    |
    | Register here all your APIs extended from Bulbadev\Autodoc\ApiVersions\Base::class.
    | Command 'autodoc:generate' will offer documentation from this array
    */

    'api_versions' => [
        \Bulbadev\Autodoc\ApiVersions\Example::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Tags count
    |--------------------------------------------------------------------------
    |
    | Number of tags for 1 endpoint for further grouping.
    | No more than two are recommended.
    */

    'tags_count' => 1,

    /*
    |--------------------------------------------------------------------------
    | Default file doc extension
    |--------------------------------------------------------------------------
    |
    | Now available only json ext
    */

    'file_ext' => 'json',

    /*
    |--------------------------------------------------------------------------
    | Default descriptions of code statuses
    |--------------------------------------------------------------------------
    */

    'code-descriptions' => [
        '200' => 'Successfully',
        '201' => 'Created successfully',
        '202' => 'Accepted for processing',
        '204' => 'No content',
        '400' => 'Bad or invalid request',
        '401' => 'Not authorized',
        '403' => 'Permission denied',
        '404' => 'Not found',
        '422' => 'Validation errors',
        '500' => 'Server errors',
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Collector Class
    |--------------------------------------------------------------------------
    |
    | Class of data collector, which will collect and save documentation
    | It can be your own data collector class which should be inherited from
    | Bulbadev\Autodoc\Collectors\Collector interface.
    | Now available only File::class
    |
    | WARNING! If you start your test in isolation, you must use File::class.
    | Every request will be save in file directly (without cache).
    */

    'data_collector' => \Bulbadev\Autodoc\Collectors\File::class,
];
