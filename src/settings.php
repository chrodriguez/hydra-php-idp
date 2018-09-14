<?php
$debug_mode = isset($_ENV['DEBUG']) ? strcasecmp($_ENV['DEBUG'],'true') == 0 : false;
return [
    'settings' => [
        'displayErrorDetails' => $debug_mode,
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => $debug_mode ? \Monolog\Logger::DEBUG : \Monolog\Logger::INFO,
        ],
        'hydra' => [
          'host' => isset($_ENV['HYDRA_HOST']) ? $_ENV['HYDRA_HOST'] : 'http://localhost:4445'
        ]
    ],
];
