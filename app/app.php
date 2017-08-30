<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

// Register providers.
$app->register(new Silex\Provider\HttpFragmentServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../app/views',
]);

// Enable local debugging.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || (in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1', '192.168.56.1')) || php_sapi_name() === 'cli-server')
) {
    // Create Symfony profiler.
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), [
        'profiler.cache_dir' => __DIR__.'/../cache/profiler',
        'profiler.mount_prefix' => '/_profiler',
    ]);
    $app['debug'] = true;
}

// Initialize routes.
/*
$app->get('/hello/{name}', function ($name) use ($app) {
    return $app['twig']->render('startup/hello.html.twig', [
        'name' => $name,
    ]);
});
*/

$app->mount('/', new Acme\Controller\MainControllerProvider());

return $app;
