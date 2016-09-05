<?php
declare(strict_types=1);

namespace AppTest\Entity\Exception;

use App\Entity\Book;
use App\Entity\Exception\BookNotAvailable;

/**
 * @covers \App\Entity\Exception\BookNotAvailable
 */
final class BookNotAvailableTest extends \PHPUnit_Framework_TestCase
{
    public function testFromUuid()
    {
        $exception = BookNotAvailable::fromBook(Book::fromName('foo'));

        self::assertInstanceOf(BookNotAvailable::class, $exception);
        self::assertStringMatchesFormat('%s is not available', $exception->getMessage());
    }
}
