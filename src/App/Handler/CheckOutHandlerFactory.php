<?php
declare(strict_types = 1);

namespace App\Handler;

use App\Service\Book\FindBookByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

/**
 * @codeCoverageIgnore
 */
final class CheckOutHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return CheckOutHandler
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CheckOutHandler
    {
        return new CheckOutHandler(
            $container->get(FindBookByUuidInterface::class),
            $container->get(EntityManagerInterface::class)
        );
    }
}
