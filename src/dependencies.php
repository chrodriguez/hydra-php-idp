<?php
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
