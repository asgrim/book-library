<?php
declare(strict_types = 1);

namespace App\Handler;

use App\Service\Book\FindBookByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

/**
 * @codeCoverageIgnore
 */
final class CheckInHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return CheckInHandler
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CheckInHandler
    {
        return new CheckInHandler(
            $container->get(FindBookByUuidInterface::class),
            $container->get(EntityManagerInterface::class)
        );
    }
}
