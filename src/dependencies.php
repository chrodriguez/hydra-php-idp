<?php
require __DIR__.'/hydra_adapter.php';

// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['hydra'] = function ($c) {
    $config = new \Hydra\SDK\Configuration();
    $config->setHost($c->get('settings')['hydra']['host']);
    $client = new \Hydra\SDK\ApiClient($config);
    $hydra = new Hydra\SDK\Api\OAuth2Api($client);
    $c->get('logger')->debug("Initialized Hydra with host ". $config->getHost());
    return $hydra;
};

$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};

$container['db'] = function($c) {
    $settings = $c->get('settings')['db'];
    $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['name'],
        $settings['username'], $settings['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container['auth_sql'] = function($c) {
    $settings = $c->get('settings')['db'];
    return $settings['auth-sql-query'];
};

$container['userinfo_sql'] = function($c) {
    $settings = $c->get('settings')['db'];
    return $settings['userinfo-sql-query'];
};

$container['loginRememberFor'] = function($c) {
    $settings = $c->get('settings')['hydra'];
    return intval($settings['login_remember_for']);
};

$container['consentRememberFor'] = function($c) {
    $settings = $c->get('settings')['hydra'];
    return intval($settings['consent_remember_for']);
};

$container['hydraAdapter'] = function($c) {
  return new HydraAdapter(array(
    'pdo' => $c->get('db'),
    'user_sql' => $c->get('userinfo_sql'),
    'auth_sql' => $c->get('auth_sql'),
  ));
};


