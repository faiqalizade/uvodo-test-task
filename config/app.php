<?php
return [
    'source_config' => [

        'db' => [
            'host' => '127.0.0.1',
            'username' => 'root',
            'password' => 'root',
            'dbname' => 'uvodo',
            'port' => 8889
        ],

        'json' => [
            'filepath' => 'filestore/json'
        ],

        'csv' => [
            'filepath' => 'filestpre/csv'
        ]
    ],

    'data_sources' => [
        'db' => \Core\Facades\DataSource\DB::class,
        'csv' => \Core\Facades\DataSource\CSV::class,
        'json' => \Core\Facades\DataSource\JSON::class,
    ],

    'data_source' => 'json'
];
