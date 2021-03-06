<?php

$loader = require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

// Register providers.
$app->register(new Silex\Provider\HttpFragmentServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../app/views',
]);
// Register Doctrine
$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/../app/db/app.db',
        'dbname' => 'tweeter',
        'charset' => 'utf8mb4',
    ],
    'db.orm.proxies_dir'  => __DIR__ . '/../app/cache/doctrine/proxy',
]);
// Register entity manager.
$app->register(new Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider(), [
    'orm.proxies_dir' => __DIR__ . '/../app/cache/doctrine/proxy',
    'orm.em.options' => [
        'mappings' => [
            [
                'type' => 'annotation',
                'namespace' => 'Acme\Entity',
                'path' => __DIR__ . '/../src/Acme/Entity',
                'use_simple_annotation_reader' => false,
            ],
        ],
    ],
]);
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$users = function () use ($app) {
    return new Acme\Provider\UserProvider($app['db']);
};
// Register security for authentication

$app->register(new Silex\Provider\SecurityServiceProvider(), [
    'security.firewalls' => [
        'login' => array(
            'pattern' => '^/login$',
        ),
        'secured' => [
            'pattern' => '^.*$',
            'form' => [
                'login_path' => '/login',
                'default_target_path' => '/tweet/',
                'check_path' => '/login_check'
            ],
            'logout' => [
                'logout_path' => '/logout',
                'target_url' => '/',
                'invalidate_session' => true
            ],
            'users' => $users,
        ],
    ]
]);
// Register Monolog
$app->register(new Silex\Provider\MonologServiceProvider(), [
    'monolog.logfile' => __DIR__ . '/../app/log/dev.log',
]);
// Register Forms & CSRF
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\CsrfServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), [
    'locale_fallbacks' => ['en'],
]);
$app->register(new Silex\Provider\ValidatorServiceProvider());

// Enable local debugging.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || (in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', 'fe80::1', '::1', '192.168.56.1']) || php_sapi_name() === 'cli-server')
) {
    // Create Symfony profiler in dev mode.
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), [
        'profiler.cache_dir' => __DIR__ . '/../app/cache/profiler',
        'profiler.mount_prefix' => '/_profiler',
    ]);
    $app['debug'] = true;
}

// Initialize routes.
$app->mount('/', new Acme\Controller\MainControllerProvider());
$app->mount('/tweet', new Acme\Controller\TweetControllerProvider());

return $app;
