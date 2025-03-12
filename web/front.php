<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Simplex\StringResponseListener;

$routes = include __DIR__.'/../src/app.php';
$container = include __DIR__.'/../src/container.php';

$request = Request::createFromGlobals();


$container->register('listener.string_response', StringResponseListener::class);
$container->getDefinition('dispatcher')
    ->addMethodCall('addSubscriber', [new Reference('listener.string_response')])
;

$container->setParameter('debug', true);

echo $container->getParameter('debug');

$container->setParameter('charset', 'UTF-8');

$container->register('listener.response', ResponseListener::class)
    ->setArguments(['%charset%'])
;

$response = $container->get('framework')->handle($request);

$response->send();