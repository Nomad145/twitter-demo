<?php

use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Symfony\Component\HttpFoundation\RequestMatcher;

// include the prod configuration
require __DIR__.'/prod.php';

// enable the debug mode
$app['debug'] = true;

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../var/logs/silex_test.log',
));

// Override the firewall for functional tests.  Some of this duplication needs
// refactoring in the future.
$app['security.firewalls'] = [
    'unauthenticated' => [
        'anonymous' => true,
        'pattern' => new RequestMatcher('^/(login|register|whoami)')
    ],
    'test' => [
        'pattern' => new RequestMatcher('^/'),
        'http' => true,
        'form' => [
            'login_path' => '/login',
            'check_path' => '/home/login_check'
        ],
        'logout' => [
            'logout_path' => '/logout',
            'invalidate_session' => true
        ],
        'users' => [
            'test' => [
                'ROLE_USER',
                '$2y$10$3i9/lVd8UOFIJ6PAMFt8gu3/r5g0qeCJvoSlLCsvMTythye19F77a'
            ]
        ]
    ]
];
