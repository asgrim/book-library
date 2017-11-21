<?php
declare(strict_types = 1);

namespace AppTest\Service\Book;

use App\Entity\Book;
use App\Service\Book\DoctrineFindBookByUuid;
use App\Service\Book\Exception\BookNotFound;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DoctrineFindBookByUuidTest extends TestCase
{
    public function testExceptionIsThrownWhenRepositoryReturnsNull() : void
    {
        $uuid = Uuid::uuid4();

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects(self::once())->method('find')->with((string)$uuid)->willReturn(null);

        $findBook = new DoctrineFindBookByUuid($repository);

        $this->expectException(BookNotFound::class);
        $findBook->__invoke($uuid);
    }

    public function testBookIsReturned() : void
    {
        $book = Book::fromName('foo');
        $uuid = $book->getId();

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects(self::once())->method('find')->with($uuid)->willReturn($book);

        $findBook = new DoctrineFindBookByUuid($repository);
        $findBook->__invoke(Uuid::fromString($uuid));
    }
}
