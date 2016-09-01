<?php
declare(strict_types=1);

use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

return [
    'dependencies' => [
        'factories' => [
            Helper\ServerUrlMiddleware::class => Helper\ServerUrlMiddlewareFactory::class,
            Helper\UrlHelperMiddleware::class => Helper\UrlHelperMiddlewareFactory::class,
        ],
    ],
    'middleware_pipeline' => [
        'always' => [
            'middleware' => [
                Helper\ServerUrlMiddleware::class,
            ],
            'priority' => 10000,
        ],
        'routing' => [
            'middleware' => [
                ApplicationFactory::ROUTING_MIDDLEWARE,
                Helper\UrlHelperMiddleware::class,
                ApplicationFactory::DISPATCH_MIDDLEWARE,
            ],
            'priority' => 1,
        ],
        'error' => [
            'middleware' => [
            ],
            'error'    => true,
            'priority' => -10000,
        ],
    ],
];
