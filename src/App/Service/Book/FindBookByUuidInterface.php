<?php
declare(strict_types = 1);

namespace App\Service\Book;

use App\Entity\Book;
use Ramsey\Uuid\UuidInterface;

interface FindBookByUuidInterface
{
    /**
     * @param UuidInterface $slug
     * @return Book
     * @throws Exception\BookNotFound
     */
    public function __invoke(UuidInterface $slug): Book;
}
