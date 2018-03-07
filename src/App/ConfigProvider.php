<?php

declare(strict_types=1);

namespace App;

use PSR7Sessions\Storageless\Http\SessionMiddleware;
use Zend\ServiceManager\Factory\InvokableFactory;

final class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'factories'  => [
                Handler\CheckOutHandler::class => Handler\CheckOutHandlerFactory::class,
                Handler\CheckInHandler::class => Handler\CheckInHandlerFactory::class,
                Middleware\AuthenticationMiddleware::class => InvokableFactory::class,
                Middleware\ErrorCatchingMiddleware::class => InvokableFactory::class,
                Service\Book\FindBookByUuidInterface::class => Service\Book\DoctrineFindBookByUuidFactory::class,
                SessionMiddleware::class => Middleware\SessionMiddlewareFactory::class,
                Middleware\FlushingMiddleware::class => Middleware\FlushingMiddlewareFactory::class,
            ],
        ];
    }
}
