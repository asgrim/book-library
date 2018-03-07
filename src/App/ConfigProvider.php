<?php

declare(strict_types=1);

namespace App;

use ContainerInteropDoctrine\EntityManagerFactory;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\DBAL\Driver\PDOPgSql\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use Zend\ServiceManager\Factory\InvokableFactory;

final class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'doctrine' => $this->getDoctrine(),
        ];
    }

    private function getDependencies() : array
    {
        return [
            'factories'  => [
                EntityManagerInterface::class => EntityManagerFactory::class,
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

    private function getDoctrine(): array
    {
        return [
            'connection' => [
                'orm_default' => [
                    'driver_class' => Driver::class,
                    'params' => [
                        'url' => 'configure this in local.php',
                    ],
                ],
            ],
            'driver' => [
                'orm_default' => [
                    'class' => MappingDriverChain::class,
                    'drivers' => [
                        'App\Entity' => 'app_entity',
                    ],
                ],
                'app_entity' => [
                    'class' => AnnotationDriver::class,
                    'cache' => 'array',
                    'paths' => [
                        __DIR__ . '/../../src/App/Entity',
                    ],
                ],
            ],
        ];
    }
}
