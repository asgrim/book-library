<?php
declare(strict_types = 1);

namespace App\Action;

use App\Service\Book\FindBookByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

/**
 * @codeCoverageIgnore
 */
final class CheckOutActionFactory
{
    public function __invoke(ContainerInterface $container) : CheckOutAction
    {
        return new CheckOutAction(
            $container->get(FindBookByUuidInterface::class),
            $container->get(EntityManagerInterface::class)
        );
    }
}
