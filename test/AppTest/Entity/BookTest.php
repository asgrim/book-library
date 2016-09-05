<?php
declare(strict_types=1);

namespace AppTest\Entity\Exception;

use App\Entity\Book;
use App\Entity\Exception\BookAlreadyStocked;
use App\Entity\Exception\BookNotAvailable;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Entity\Book
 */
final class BookTest extends \PHPUnit_Framework_TestCase
{
    public function testIdReturnsValidUuidString()
    {
        $book = Book::fromName('foo');
        self::assertSame($book->getId(), (string)Uuid::fromString($book->getId()));
    }

    public function testGetName()
    {
        $book = Book::fromName('foo');
        self::assertSame('foo', $book->getName());
    }

    public function testExceptionIsThrownWhenBookCheckedOutTwice()
    {
        $book = Book::fromName('foo');

        $this->expectException(BookNotAvailable::class);
        $book->checkOut();
        $book->checkOut();
    }

    public function testExceptionIsThrownWhenBookCheckedInTwice()
    {
        $book = Book::fromName('foo');

        $this->expectException(BookAlreadyStocked::class);
        $book->checkIn();
        $book->checkIn();
    }

    public function testBookCanBeCheckedOutAndBackIn()
    {
        $book = Book::fromName('foo');
        $book->checkOut();
        $book->checkIn();
        $book->checkOut();
        $book->checkIn();
    }
}
