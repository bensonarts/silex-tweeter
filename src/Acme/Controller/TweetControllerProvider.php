<?php

namespace Acme\Controller;

use Acme\Entity\Tweet;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
        $tweet = new Tweet();

        $form = $app['form.factory']->createBuilder(FormType::class, $tweet)
            ->add('message', TextareaType::class, [
                'label' => 'Tweet',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            // do something with the data

            // redirect somewhere
            return $app->redirect('/tweet/asdf');
        }

        // display the form
        return $app['twig']->render('tweet/main.html.twig', [
            'form' => $form->createView()]
        );
    }

}
