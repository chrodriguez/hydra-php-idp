<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

// Login show form
$app->get('/login[/]', function (Request $request, Response $response, array $args) {
    $challenge = $request->getParam('login_challenge');
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    $errors = array();
    if (is_null($challenge)) {
      array_push($errors, "Empty login challenge not allowed");
    } else {
      try{
        $this->logger->debug("/login with challenge ${challenge}");
        $login_response = $this->hydra->getLoginRequest($challenge);
        if ( $login_response->getSkip() ) {
          $result = $this->hydra->acceptLoginRequest($challenge, array(
            'subject' => $login_response->getSubject()));
          return $response->withRedirect($result->getRedirectTo());
        }
      }catch(Exception $e) {
        array_push($errors, "Error trying loginRequest with HYDRA: ".$e->getMessage());
      }
    }
    $args = array(
      'csfrNameKey'   => $this->csrf->getTokenNameKey(),
      'csfrValueKey'  => $this->csrf->getTokenValueKey(),
      'challenge'     => $challenge,
      'remember'      => true,
      'errors'        => $errors,
    );
    $args['csfrName']  = $request->getAttribute($args['csfrNameKey']);
    $args['csfrValue'] = $request->getAttribute($args['csfrValueKey']);
    return $this->renderer->render($response, 'login.phtml', $args);
});

// Login process
$app->post('/login[/]', function (Request $request, Response $response, array $args) {
    $challenge = $request->getParam('challenge');
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    $remember = (strtolower($request->getParam('remember')) === 'on');
    $this->logger->debug("POST /login with challenge: ${challenge}, username: ${username}, password: ${password}");
    $errors = array();
    if (is_null($challenge)) {
      array_push($errors, "Empty login challenge not allowed");
    } else {
      if ( !$this->hydraAdapter->doLogin($username, $password) ) {
        array_push($errors, "Username or password incorrect");
      }
    }
    try {
      if ( count($errors) == 0 ) {
        $result = $this->hydra->acceptLoginRequest($challenge, array(
          'subject' => $username,
          'remember_for' => $this->loginRememberFor,
          'remember' => $remember));
        $this->logger->debug("Redirecting to ".$result->getRedirectTo());
        return $response->withRedirect($result->getRedirectTo());
      }
    }catch(Exception $e) {
     array_push($errors, "Error accepting login with HYDRA: ".$e->getMessage());
    }
    if ( count($errors) > 0 ) {
      $args = array(
        'csfrNameKey'   => $this->csrf->getTokenNameKey(),
        'csfrValueKey'  => $this->csrf->getTokenValueKey(),
        'username'      => $username,
        'remember'      => $remember,
        'challenge'     => $challenge,
        'errors'        => $errors,
      );
      $args['csfrName']  = $request->getAttribute($args['csfrNameKey']);
      $args['csfrValue'] = $request->getAttribute($args['csfrValueKey']);
      return $this->renderer->render($response, 'login.phtml', $args);
    }
});


$app->get('/consent[/]', function (Request $request, Response $response, array $args) {
    $challenge = $request->getParam('consent_challenge');
    $errors = array();
    if (is_null($challenge)) {
      array_push($errors, "Empty login challenge not allowed");
    } else {
      try{
        $this->logger->debug("/consent with challenge ${challenge}");
        $consent_response = $this->hydra->getConsentRequest($challenge);
        if ( $consent_response->getSkip() ) {
          $result = $this->hydra->acceptConsentRequest($challenge, array(
            'grant_scope' => $consent_response->getRequestedScope(),
            'session' => array(
              'id_token' => $this->hydraAdapter->getIdTokenData($consent_response->getSubject())
            )
          ));
          return $response->withRedirect($result->getRedirectTo());
        }
      }catch(Exception $e) {
        array_push($errors, "Error trying loginRequest with HYDRA: ".$e->getMessage());
      }
    }
    $args = array(
      'csfrNameKey'     => $this->csrf->getTokenNameKey(),
      'csfrValueKey'    => $this->csrf->getTokenValueKey(),
      'challenge'       => $challenge,
      'errors'          => $errors,
    );
    $args['csfrName']  = $request->getAttribute($args['csfrNameKey']);
    $args['csfrValue'] = $request->getAttribute($args['csfrValueKey']);
    if (isset($consent_response)) {
      $args['requested_scope'] = $consent_response->getRequestedScope();
      $args['username'] = $consent_response->getSubject();
      $args['client'] = $consent_response->getClient();
    }
    return $this->renderer->render($response, 'consent.phtml', $args);
});

$app->post('/consent[/]', function (Request $request, Response $response, array $args) {
    $challenge = $request->getParam('challenge');
    $submit = $request->getParam('submit');
    $errors = array();
    $remember = (strtolower($request->getParam('remember')) === 'on');
    if ( !strcasecmp('Deny access', $submit)) {
      die('To be implemented');
    }
    if ( !strcasecmp('Allow access', $submit)) {
      $grants = $request->getParam('grant_scope');
      $username = $request->getParam('username');
      try {
        $result = $this->hydra->acceptConsentRequest($challenge, array(
          'grant_scope' => $grants,
          'remember_for' => $this->consentRememberFor,
          'remember' => $remember,
          'session' => array(
            'id_token' => $this->hydraAdapter->getIdTokenData($username),
          )
        ));
        return $response->withRedirect($result->getRedirectTo());
      }catch(Exception $e) {
        array_push($errors, "Error trying loginRequest with HYDRA: ".$e->getMessage());
        throw $e;
      }
    }
});
