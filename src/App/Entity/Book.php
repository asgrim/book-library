<?php
declare(strict_types = 1);

namespace App\Entity;

use Ramsey\Uuid\Uuid;

final class Book
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var bool
     */
    private $inStock = true;

    public function __construct()
    {
        $this->id = (string)Uuid::uuid4();
    }

    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return void
     * @throws \App\Entity\Exception\BookNotAvailable
     */
    public function checkOut()
    {
        if (!$this->inStock) {
            throw Exception\BookNotAvailable::fromBook($this);
        }

        $this->inStock = false;
    }

    /**
     * @return void
     * @throws \App\Entity\Exception\BookAlreadyStocked
     */
    public function checkIn()
    {
        if ($this->inStock) {
            throw Exception\BookAlreadyStocked::fromBook($this);
        }

        $this->inStock = true;
    }
}
