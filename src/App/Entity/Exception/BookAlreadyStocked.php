<?php
declare(strict_types = 1);

namespace App\Entity\Exception;

use App\Entity\Book;

final class BookAlreadyStocked extends \DomainException
{
    public static function fromBook(Book $book) : self
    {
        return new self(sprintf('%s is already in stock', $book->getName()));
    }
}
