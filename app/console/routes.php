#!/usr/bin/env php
<?php

$app = require __DIR__ . '/../../app/app.php';

echo 'listing routes...' . PHP_EOL;

$routes = $app['routes']->all();
foreach($routes as $route) {
    $cr = new ReflectionFunction($route->getDefault('_controller'));
    echo sprintf("%s %s => %s@%d\n",
        $route->getRequirement('_method'),
        $route->getPattern(),
        str_replace(dirname(__DIR__) . '/', '', $cr->getFileName()),
        $cr->getStartLine()
    );
}
