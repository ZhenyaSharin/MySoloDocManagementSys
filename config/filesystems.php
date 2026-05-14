<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
     */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
     */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public_doc' => [
            'driver' => 'local',
            'root' => storage_path('app/public/docs'),
            'url' => env('APP_URL') . '/storage/docs',
            'visibility' => 'public',
        ],

        'public_pdf' => [
            'driver' => 'local',
            'root' => storage_path('app/public/pdfs'),
            'url' => env('APP_URL') . '/storage/pdfs',
            'visibility' => 'public',
        ],

        'public_etc' => [
            'driver' => 'local',
            'root' => storage_path('app/public/etc'),
            'url' => env('APP_URL') . '/storage/etc',
            'visibility' => 'public',
        ],

        'public_add' => [
            'driver' => 'local',
            'root' => storage_path('app/public/additions'),
            'url' => env('APP_URL') . '/storage/additions',
            'visibility' => 'public',
        ],

        'public_images' => [
            'driver' => 'local',
            'root' => storage_path('app/public/images'),
            'url' => env('APP_URL') . '/storage/images',
            'visibility' => 'public',
        ],

        'public_orig' => [
            'driver' => 'local',
            'root' => storage_path('app/public/origs'),
            'url' => env('APP_URL') . '/storage/origs',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
     */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
