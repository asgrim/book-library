<?php
declare(strict_types = 1);

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Helper\HelperSet;

$container = require __DIR__ . '/container.php';

return new HelperSet([
    'em' => new EntityManagerHelper(
        $container->get(EntityManagerInterface::class)
    ),
]);
