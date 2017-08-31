<?php

namespace Acme\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class MainControllerProvider implements ControllerProviderInterface
{
    /**
     * Connect controllers
     *
     * @param Silex\Application $app
     * @return array $controllers
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', 'Acme\Controller\MainControllerProvider::main');
        $controllers->get('/hello/{name}', 'Acme\Controller\MainControllerProvider::hello');

        return $controllers;
    }

    /**
     * Home page controller
     *
     * @param Silex\Application
     * @return Response
     */
    public function main(Application $app)
    {
        return $app['twig']->render('startup/main.html.twig');
    }

    /**
     * Hello user page controller
     *
     * @param Silex\Application
     * @param string $name User's name.
     * @return Response
     */
    public function hello(Application $app, $name)
    {
        return $app['twig']->render('startup/hello.html.twig', [
            'name' => $name,
        ]);
    }

}
