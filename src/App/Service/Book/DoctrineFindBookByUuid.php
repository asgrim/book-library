<?php
declare(strict_types = 1);

namespace App\Service\Book;

use App\Entity\Book;
use Doctrine\Common\Persistence\ObjectRepository;
use Ramsey\Uuid\UuidInterface;

class DoctrineFindBookByUuid implements FindBookByUuidInterface
{
    /**
     * @var ObjectRepository
     */
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UuidInterface $id
     * @return Book
     * @throws Exception\BookNotFound
     */
    public function __invoke(UuidInterface $id): Book
    {
        /** @var Book|null $book */
        $book = $this->repository->find((string)$id);

        if (null === $book) {
            throw Exception\BookNotFound::fromUuid($id);
        }

        return $book;
    }
}
