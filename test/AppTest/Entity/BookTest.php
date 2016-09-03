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
        $book = new Book();
        self::assertSame($book->getId(), (string)Uuid::fromString($book->getId()));
    }

    public function testExceptionIsThrownWhenBookCheckedOutTwice()
    {
        $book = new Book();

        $this->expectException(BookNotAvailable::class);
        $book->checkOut();
        $book->checkOut();
    }

    public function testExceptionIsThrownWhenBookCheckedInTwice()
    {
        $book = new Book();

        $this->expectException(BookAlreadyStocked::class);
        $book->checkIn();
        $book->checkIn();
    }

    public function testBookCanBeCheckedOutAndBackIn()
    {
        $book = new Book();
        $book->checkOut();
        $book->checkIn();
        $book->checkOut();
        $book->checkIn();
    }
}
