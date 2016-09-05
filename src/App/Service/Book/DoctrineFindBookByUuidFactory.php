<?php
declare(strict_types = 1);

namespace App\Service\Book;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

/**
 * @codeCoverageIgnore
 */
final class DoctrineFindBookByUuidFactory
{
    public function __invoke(ContainerInterface $container) : callable
    {
        return new DoctrineFindBookByUuid($container->get(EntityManagerInterface::class)->getRepository(Book::class));
    }
}
