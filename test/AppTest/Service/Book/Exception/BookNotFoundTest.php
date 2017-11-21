<?php
declare(strict_types=1);

namespace AppTest\Service\Book\Exception;

use App\Service\Book\Exception\BookNotFound;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \App\Service\Book\Exception\BookNotFound
 */
final class BookNotFoundTest extends TestCase
{
    public function testFromUuid() : void
    {
        $exception = BookNotFound::fromUuid(Uuid::uuid4());

        self::assertInstanceOf(BookNotFound::class, $exception);
        self::assertStringMatchesFormat('Book with UUID %s is not found', $exception->getMessage());
    }
}
