<?php
declare(strict_types=1);

namespace AppTest\Entity\Exception;

use App\Entity\Book;
use App\Entity\Exception\BookNotAvailable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\Exception\BookNotAvailable
 */
final class BookNotAvailableTest extends TestCase
{
    public function testFromUuid() : void
    {
        $exception = BookNotAvailable::fromBook(Book::fromName('foo'));

        self::assertInstanceOf(BookNotAvailable::class, $exception);
        self::assertStringMatchesFormat('%s is not available', $exception->getMessage());
    }
}
