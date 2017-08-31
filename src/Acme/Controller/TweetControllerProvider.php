<?php

namespace Acme\Controller;

use Acme\Form\TweetType;
use Acme\Entity\Tweet;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class TweetControllerProvider implements ControllerProviderInterface
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
        $controllers->match('/', 'Acme\Controller\TweetControllerProvider::main');

        return $controllers;
    }

    /**
     * Home page controller
     *
     * @param Silex\Application $app
     * @param Request $request
     * @return Response
     */
    public function main(Application $app, Request $request)
    {
        $em = $app['orm.em'];
        $token = $app['security.token_storage']->getToken();
        if (null !== $token) {
            $userId = $token->getUser()->getId();
        } else {
            return $app->redirect('/login');
        }

        $tweet = new Tweet();
        $tweets = $em->getRepository('Acme\Entity\Tweet')->findByUser($app, $userId);
        $form = $app['form.factory']->create(TweetType::class, $tweet);
        $form->handleRequest($request);

        // Validate form.
        if ($form->isValid()) {
            $data = $form->getData();

            // Save tweet
            $tweet->setUser($userId);
            $em->persist($tweet);
            $em->flush();

            return $app->redirect('/tweet/');
        }

        // display the form
        return $app['twig']->render('tweet/main.html.twig', [
                'form' => $form->createView(),
                'tweets' => $tweets,
            ]
        );
    }

}
