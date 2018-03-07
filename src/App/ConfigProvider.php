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
                Action\CheckOutAction::class => Action\CheckOutActionFactory::class,
                Action\CheckInAction::class => Action\CheckInActionFactory::class,
                Middleware\AuthenticationMiddleware::class => InvokableFactory::class,
                Middleware\ErrorCatchingMiddleware::class => InvokableFactory::class,
                Service\Book\FindBookByUuidInterface::class => Service\Book\DoctrineFindBookByUuidFactory::class,
                SessionMiddleware::class => Middleware\SessionMiddlewareFactory::class,
                Middleware\FlushingMiddleware::class => Middleware\FlushingMiddlewareFactory::class,
            ],
        ];
    }
}
