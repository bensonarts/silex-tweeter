<?php

$app = new Silex\Application();

$app['autoloader']->registerNamespace('Acme', __DIR__.'/../src');

