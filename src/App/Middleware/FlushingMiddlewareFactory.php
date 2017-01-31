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
    public function __invoke(ContainerInterface $container) : callable
    {
        return new FlushingMiddleware(
            $container->get(EntityManagerInterface::class)
        );
    }
}
