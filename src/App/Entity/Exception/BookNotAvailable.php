<?php
declare(strict_types = 1);

namespace App\Entity\Exception;

use App\Entity\Book;

final class BookNotAvailable extends \DomainException
{
    public static function fromBook(Book $book) : self
    {
        return new self(sprintf('Book with UUID %s is not available', $book->getId()));
    }
}
