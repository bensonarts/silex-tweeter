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
        'driver' => 'pdo_mysql',
        'dbhost' => 'local-db',
        'dbname' => 'tweeter',
        'user' => 'root',
        'password' => 'ansira',
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
            ],
        ],
    ],
]);
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

// TODO Refactor into UserModel.
$users = [
    'admin' => ['ROLE_ADMIN', '$2y$10$3i9/lVd8UOFIJ6PAMFt8gu3/r5g0qeCJvoSlLCsvMTythye19F77a']
];
// Register security for authentication

$app->register(new Silex\Provider\SecurityServiceProvider(), [
    'security.firewalls' => [
        'admin' => [
            'pattern' => '^/admin/',
            'form' => [
                'login_path' => '/login',
                'default_target_path' => '/admin/',
                'check_path' => '/admin/login_check'
            ],
            'logout' => [
                'logout_path' => '/admin/logout',
                'target_url' => 'admin',
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
    // Create Symfony profiler.
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), [
        'profiler.cache_dir' => __DIR__ . '/../app/cache/profiler',
        'profiler.mount_prefix' => '/_profiler',
    ]);
    $app['debug'] = true;
}

// Initialize routes.
$app->mount('/', new Acme\Controller\MainControllerProvider());
$app->mount('/tweet', new Acme\Controller\TweetControllerProvider());

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('auth/login.html.twig', [
        'error' => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ]);
});

return $app;
