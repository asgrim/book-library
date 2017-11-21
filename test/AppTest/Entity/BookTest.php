<?php
declare(strict_types=1);

namespace AppTest\Entity\Exception;

use App\Entity\Book;
use App\Entity\Exception\BookAlreadyStocked;
use App\Entity\Exception\BookNotAvailable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Entity\Book
 */
final class BookTest extends TestCase
{
    public function testIdReturnsValidUuidString() : void
    {
        $book = Book::fromName('foo');
        self::assertSame($book->getId(), (string)Uuid::fromString($book->getId()));
    }

    public function testGetName() : void
    {
        $book = Book::fromName('foo');
        self::assertSame('foo', $book->getName());
    }

    public function testExceptionIsThrownWhenBookCheckedOutTwice() : void
    {
        $book = Book::fromName('foo');

        $this->expectException(BookNotAvailable::class);
        $book->checkOut();
        $book->checkOut();
    }

    public function testExceptionIsThrownWhenBookCheckedInTwice() : void
    {
        $book = Book::fromName('foo');

        $this->expectException(BookAlreadyStocked::class);
        $book->checkIn();
        $book->checkIn();
    }

    public function testBookCanBeCheckedOutAndBackIn() : void
    {
        $book = Book::fromName('foo');
        self::assertTrue($book->isInStock());
        $book->checkOut();
        self::assertFalse($book->isInStock());
        $book->checkIn();
        self::assertTrue($book->isInStock());
    }
}
