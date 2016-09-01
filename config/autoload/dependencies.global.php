<?php
declare(strict_types=1);

use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

return [
    'dependencies' => [
        'invokables' => [
            Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
            App\Action\HomePageAction::class => App\Action\HomePageAction::class,
        ],
        'factories' => [
            Application::class => ApplicationFactory::class,
            Helper\UrlHelper::class => Helper\UrlHelperFactory::class,
        ],
    ],
];
