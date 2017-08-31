<?php

namespace Acme\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthControllerProvider implements ControllerProviderInterface
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
        $controllers->get('/', 'Acme\Controller\TweetControllerProvider::main');

        return $controllers;
    }

    /**
     * Login page controller
     *
     * @param Silex\Application
     * @return Response
     */
    public function login(Application $app)
    {
        return $app['twig']->render('auth/login.html.twig');
    }

}
