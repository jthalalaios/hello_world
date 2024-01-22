<?php

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/Migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/Seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'production' => [
                'adapter' => 'mysql',
                'host' => 'mysql-hello_world:5346',
                'name' => 'hello_world_db',
                'user' => 'root',
                'pass' => 'hello_world',
                'port' => '5346',
                'charset' => 'utf8',
            ],
            'development' => [
                'adapter' => 'mysql',
                'host' => 'mysql-hello_world:5346',
                'name' => 'hello_world_db',
                'user' => 'root',
                'pass' => 'hello_world',
                'port' => '5346',
                'charset' => 'utf8',
            ],
            'testing' => [
                'adapter' => 'mysql',
                'host' => 'localhost',
                'name' => 'testing_db',
                'user' => 'root',
                'pass' => '',
                'port' => '3306',
                'charset' => 'utf8',
            ]
        ],
        'version_order' => 'creation'
    ];
