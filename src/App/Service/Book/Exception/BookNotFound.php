<?php
declare(strict_types = 1);

namespace App\Service\Book\Exception;

use Ramsey\Uuid\UuidInterface;

final class BookNotFound extends \RuntimeException
{
    public static function fromUuid(UuidInterface $uuid) : self
    {
        return new self(sprintf('Book with UUID %s is not found', (string)$uuid));
    }
}
