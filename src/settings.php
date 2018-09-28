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
          'host' => isset($_ENV['HYDRA_HOST']) ? $_ENV['HYDRA_HOST'] : 'http://localhost:4445',
          'login_remember_for' => isset($_ENV['HYDRA_LOGIN_REMEMBER_FOR']) ? $_ENV['HYDRA_LOGIN_REMEMBER_FOR'] : 3600,
          'consent_remember_for' => isset($_ENV['HYDRA_CONSENT_REMEMBER_FOR']) ? $_ENV['HYDRA_CONSENT_REMEMBER_FOR'] : 3600
        ],
        'db' => [
          'host'      => isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'localhost',
          'name'      => isset($_ENV['DB_NAME']) ? $_ENV['DB_NAME'] : 'idp-sample',
          'username'  => isset($_ENV['DB_USERNAME']) ? $_ENV['DB_USERNAME'] : 'root',
          'password'  => isset($_ENV['DB_PASSWORD']) ? $_ENV['DB_PASSWORD'] : '',
          'auth-sql-query'      => isset($_ENV['AUTH_SQL_QUERY']) ? $_ENV['AUTH_SQL_QUERY'] : 'select * from users where username=:username and password=:password',
          'userinfo-sql-query'  => isset($_ENV['USERINFO_SQL_QUERY']) ? $_ENV['USERINFO_SQL_QUERY'] : 'select * from users where username=:username'
        ]
    ],
];
