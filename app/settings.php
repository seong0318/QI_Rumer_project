<?php
return [
    'settings' => [
        // comment this line when deploy to production environment
        'displayErrorDetails' => true,
        // View settings
        'view' => [
            'template_path' => __DIR__ . '/templates',
            'twig' => [
                'cache' => __DIR__ . '/../cache/twig',
                'debug' => true,
                'auto_reload' => true,
            ],
        ],

        // doctrine settings
        'doctrine' => [
            'meta' => [
                'entity_path' => [
                    __DIR__ . '/src/models'
                ],
                'auto_generate_proxies' => true,
                'proxy_dir' =>  __DIR__ . '/../cache/proxies',
                'cache' => null,
            ],
            'connection' => [
                'driver'   => 'pdo_mysql',
                'host'     => '127.0.0.1',
                'port'     => 3306,
                'dbname'   => 'teama-2020winter',
                'user'     => 'teama-iot',
                'password' => 'a3faser88basdf',
            ]
        ],

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../log/app.log',
        ],

        'db' => [
            'host' => '127.0.0.1',
            'user' => 'teama-iot',
            'pass' => 'a3faser88basdf',
            'dbname' => 'teama-2020winter'
        ]
    ],
];

// set database parameters based on server
// if ($_SERVER['HTTP_HOST'] == '192.168.33.99') {
//     $db_array = array(
//         'host' => '127.0.0.1',
//         'user' => 'root',
//         'pass' => '12345678',
//         'dbname' => 'rumer_local'
//     );
// } else {
//     $db_array = array(
//         'host' => '127.0.0.1',
//         'user' => 'root',
//         'pass' => '',
//         'dbname' => 'rumer_server'
//     );
// }
