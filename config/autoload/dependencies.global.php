<?php
declare(strict_types=1);

use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
            Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
            App\Middleware\AuthenticationMiddleware::class => App\Middleware\AuthenticationMiddleware::class,
            App\Middleware\ErrorCatchingMiddleware::class => App\Middleware\ErrorCatchingMiddleware::class,
        ],
        'factories' => [
            Application::class => ApplicationFactory::class,
            Helper\UrlHelper::class => Helper\UrlHelperFactory::class,

            App\Action\CheckOutAction::class => App\Action\CheckOutActionFactory::class,
            App\Action\CheckInAction::class => App\Action\CheckInActionFactory::class,

            App\Service\Book\FindBookByUuidInterface::class => App\Service\Book\DoctrineFindBookByUuidFactory::class,

            Helper\ServerUrlMiddleware::class => Helper\ServerUrlMiddlewareFactory::class,
            Helper\UrlHelperMiddleware::class => Helper\UrlHelperMiddlewareFactory::class,
            PSR7Sessions\Storageless\Http\SessionMiddleware::class => App\Middleware\SessionMiddlewareFactory::class,
            App\Middleware\FlushingMiddleware::class => App\Middleware\FlushingMiddlewareFactory::class,
        ],
    ],
];
