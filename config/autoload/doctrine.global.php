<?php
declare(strict_types = 1);

return [
    'dependencies' => [
        'factories' => [
            Doctrine\ORM\EntityManagerInterface::class => ContainerInteropDoctrine\EntityManagerFactory::class,
        ],
    ],
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driver_class' => \Doctrine\DBAL\Driver\PDOPgSql\Driver::class,
                'params' => [
                    'url' => 'configure this in local.php',
                ],
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    'App\Entity' => 'app_entity',
                ],
            ],
            'app_entity' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../../src/App/Entity',
                ],
            ],
        ],
    ],
];
