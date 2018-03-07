<?php
declare(strict_types = 1);

namespace App\Action;

use App\Service\Book\FindBookByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

/**
 * @codeCoverageIgnore
 */
final class CheckInActionFactory
{
    /**
     * @param ContainerInterface $container
     * @return CheckInAction
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CheckInAction
    {
        return new CheckInAction(
            $container->get(FindBookByUuidInterface::class),
            $container->get(EntityManagerInterface::class)
        );
    }
}
