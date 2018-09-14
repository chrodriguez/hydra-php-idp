<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/login', function (Request $request, Response $response, array $args) {
    $challenge = $request->getParam('login_challenge');
    $challenge = 'aaa';
    $this->logger->debug("/login with challenge ${challenge}");
    $login_response = $this->hydra->getLoginRequest($challenge);
    var_dump($login_response); die();

    return $this->renderer->render($response, 'login.phtml', $args);
});
