<?php
declare(strict_types=1);

use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

return [
    'dependencies' => [
        'invokables' => [
            Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
        ],
        'factories' => [
            Application::class => ApplicationFactory::class,
            Helper\UrlHelper::class => Helper\UrlHelperFactory::class,

            App\Action\CheckOutAction::class => App\Action\CheckOutActionFactory::class,
            App\Action\CheckInAction::class => App\Action\CheckInActionFactory::class,

            App\Service\Book\FindBookByUuidInterface::class => App\Service\Book\DoctrineFindBookByUuidFactory::class,
        ],
    ],
];
