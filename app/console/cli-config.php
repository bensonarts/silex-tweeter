<?php

// http://docs.doctrine-project.org/en/latest/reference/configuration.html
$app = require __DIR__ . '/../../app/app.php';
$newDefaultAnnotationDrivers = array(
    __DIR__.'/src/Acme',
);
$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ApcCache);
$driverImpl = $config->newDefaultAnnotationDriver($newDefaultAnnotationDrivers);
$config->setMetadataDriverImpl($driverImpl);
$config->setProxyDir($app['orm.proxies_dir']);
$config->setProxyNamespace('Proxies');
$em = \Doctrine\ORM\EntityManager::create($app['db.options'], $config);
$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em),
));
