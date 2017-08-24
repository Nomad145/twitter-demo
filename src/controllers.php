<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Authenticated routes.
$app->get('/', 'twitter.controller:homeAction')->bind('homepage');
$app->post('/tweet', 'twitter.controller:tweetAction');

// Unauthenticated routes.
$app->get('/register', 'auth.controller:registerAction')->bind('register');
$app->post('/register', 'auth.controller:registerAction');
$app->get('/login', 'auth.controller:loginAction');

// The existential question.
$app->get('/whoami', function () {
    return new Response("beep-boop", 418);
});

// Bootstrapped routes from Silex.
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
