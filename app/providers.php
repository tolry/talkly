<?php

// should we move this to services.php?
$app['markdown'] = $app->share(function() use ($app) {
    $ciconia = new Ciconia();
    $ciconia->addExtension($app['service.markdown.extension.topic']);

    return $ciconia;
});

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {

    $twig->getExtension('core')->setDateFormat('Y-m-d H:i:s', '%d days');

    return $twig;
}));

