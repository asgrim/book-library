<?php
declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/book/{id}/check-out', \App\Handler\CheckOutHandler::class, 'check-out');
    $app->get('/book/{id}/check-in', \App\Handler\CheckInHandler::class, 'check-in');
};
