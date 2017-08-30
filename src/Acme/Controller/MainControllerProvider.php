<?php

namespace Acme\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class MainControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', 'Acme\Controller\MainControllerProvider::main');
        $controllers->get('/hello/{name}', 'Acme\Controller\MainControllerProvider::hello');

        return $controllers;
    }

    public function main(Application $app)
    {
        return $app['twig']->render('startup/main.html.twig');
    }

    public function hello(Application $app, $name)
    {
        return $app['twig']->render('startup/hello.html.twig', [
            'name' => $name,
        ]);
    }
}
