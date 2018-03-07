<?php
declare(strict_types = 1);

namespace App\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

/**
 * @codeCoverageIgnore
 */
final class FlushingMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     * @return FlushingMiddleware
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FlushingMiddleware
    {
        return new FlushingMiddleware(
            $container->get(EntityManagerInterface::class)
        );
    }
}
