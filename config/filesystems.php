<?php

return [
    'default' => 'filament',

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        // Manga storage disk
        'manga_storage' => [
            'driver' => env('MANGA_STORAGE_DRIVER', 'public'),
            'root' => storage_path('app/public/manga'),
            'url' => env('APP_URL').'/storage/manga',
            'visibility' => 'public',
            'throw' => false,
        ],

        // FTP disk for manga storage
        'ftp_manga' => [
            'driver' => 'ftp',
            'host' => env('FTP_HOST'),
            'username' => env('FTP_USERNAME'),
            'password' => env('FTP_PASSWORD'),
            'port' => env('FTP_PORT', 21),
            'root' => env('FTP_ROOT', '/manga'),
            'passive' => true,
            'ssl' => env('FTP_SSL', false),
            'timeout' => 30,
        ],

        // Filament disk (for admin uploads)
        'filament' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
