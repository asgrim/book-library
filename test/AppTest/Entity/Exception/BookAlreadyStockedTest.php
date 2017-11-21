<?php
declare(strict_types=1);

namespace AppTest\Entity\Exception;

use App\Entity\Book;
use App\Entity\Exception\BookAlreadyStocked;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\Exception\BookAlreadyStocked
 */
final class BookAlreadyStockedTest extends TestCase
{
    public function testFromUuid() : void
    {
        $exception = BookAlreadyStocked::fromBook(Book::fromName('foo'));

        self::assertInstanceOf(BookAlreadyStocked::class, $exception);
        self::assertStringMatchesFormat('%s is already in stock', $exception->getMessage());
    }
}
