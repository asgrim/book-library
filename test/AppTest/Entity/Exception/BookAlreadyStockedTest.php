<?php
declare(strict_types=1);

namespace AppTest\Entity\Exception;

use App\Entity\Book;
use App\Entity\Exception\BookAlreadyStocked;

/**
 * @covers \App\Entity\Exception\BookAlreadyStocked
 */
final class BookAlreadyStockedTest extends \PHPUnit_Framework_TestCase
{
    public function testFromUuid()
    {
        $exception = BookAlreadyStocked::fromBook(new Book());

        self::assertInstanceOf(BookAlreadyStocked::class, $exception);
        self::assertStringMatchesFormat('Book with UUID %s is already in stock', $exception->getMessage());
    }
}
