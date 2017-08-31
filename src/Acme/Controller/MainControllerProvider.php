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
        $controllers->get('/login', 'Acme\Controller\MainControllerProvider::login');

        return $controllers;
    }

    /**
     * Home page controller
     *
     * @param Silex\Application $app
     * @return Response
     */
    public function main(Application $app)
    {
        return $app['twig']->render('startup/main.html.twig');
    }

    /**
     * Hello user page controller
     *
     * @param Silex\Application $app
     * @param string $name User's name.
     * @return Response
     */
    public function hello(Application $app, $name)
    {
        return $app['twig']->render('startup/hello.html.twig', [
            'name' => $name,
        ]);
    }

    /**
     * Login page controller
     *
     * @param Silex\Application $app
     * @param Request $request
     * @return Response
     */
    public function login(Application $app, Request $request)
    {
        return $app['twig']->render('auth/login.html.twig', [
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ]);
    }

}
