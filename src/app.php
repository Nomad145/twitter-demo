<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\SessionServiceProvider;
use App\Controller\TwitterController;
use Silex\Provider\DoctrineServiceProvider;
use App\Repository\TweetRepository;
use Silex\Provider\SecurityServiceProvider;
use App\Repository\UserRepository;
use App\Controller\AuthController;
use Symfony\Component\HttpFoundation\RequestMatcher;
use App\Form\RegisterType;
use App\Security\UserProvider;
use App\DataMapper\ObjectMapper;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use App\Entity\Tweet;
use App\Entity\User;

// Register the Application Service Providers.
$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new LocaleServiceProvider());
$app->register(new TranslationServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'dbname' => 'dbname',
        'host' => 'mariadb',
        'user' => 'dbuser',
        'password' => 'dbpass'
    ]
]);
$app->register(new DoctrineOrmServiceProvider, array(
    'orm.proxies_dir' => __DIR__.'/var/cache',
    'orm.em.options' => [
        'mappings' => [
            [
                'type' => 'annotation',
                'namespace' => 'App\Entity',
                'path' => __DIR__.'/src/App/Entity',
                'use_simple_annotation_reader' => false,
            ],
        ],
    ],
));
$app->register(new SecurityServiceProvider(), [
    'security.firewalls' => [
        'unauthenticated' => [
            'anonymous' => true,
            'pattern' => new RequestMatcher('^/(login|register|whoami)')
        ],
        'user' => [
            'pattern' => new RequestMatcher('^/'),
            'form' => [
                'login_path' => '/login',
                'check_path' => '/home/login_check'
            ],
            'logout' => [
                'logout_path' => '/logout',
                'invalidate_session' => true
            ],
            'users' => function () use ($app) {
                return new UserProvider($app['user.repository']);
            }
        ]
    ]
]);


// Register the RegisterType form as a service with the FormFactory.
$app->extend('form.types', function ($types) use ($app) {
    $types[] = new RegisterType(
        $app['security.default_encoder'],
        $app['user.repository']
    );

    return $types;
});

// Define the Repositories as services.
$app['tweet.repository'] = function () use ($app) {
    return $app['orm.repository_factory']->getRepository(
        $app['orm.em'],
        Tweet::class
    );
};

$app['user.repository'] = function () use ($app) {
    return $app['orm.repository_factory']->getRepository(
        $app['orm.em'],
        User::class
    );
};

// Define the Controllers as services.
$app['twitter.controller'] = function () use ($app) {
    return new TwitterController(
        $app['orm.em'],
        $app['session'],
        $app['security.token_storage'],
        $app['form.factory'],
        $app['twig']
    );
};

$app['auth.controller'] = function () use ($app) {
    return new AuthController(
        $app['orm.em'],
        $app['session'],
        $app['form.factory'],
        $app['twig'],
        $app['security.default_encoder'],
        $app['security.last_error']
    );
};

return $app;
