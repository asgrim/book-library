<?php
declare(strict_types=1);

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
    ],
    'routes' => [
        [
            'name' => 'check-out',
            'path' => '/book/{id}/check-out',
            'middleware' => App\Action\CheckOutAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'check-in',
            'path' => '/book/{id}/check-in',
            'middleware' => App\Action\CheckInAction::class,
            'allowed_methods' => ['GET'],
        ],
    ],
];
