<?php
declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="book")
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="NONE")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=1024, nullable=false, unique=true)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="in_stock", type="boolean", nullable=false)
     * @var bool
     */
    private $inStock = true;

    private function __construct()
    {
        $this->id = (string)Uuid::uuid4();
    }

    public static function fromName(string $name) : self
    {
        $book = new self();
        $book->name = $name;
        return $book;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @throws \App\Entity\Exception\BookNotAvailable
     */
    public function checkOut() : void
    {
        if (!$this->inStock) {
            throw Exception\BookNotAvailable::fromBook($this);
        }

        $this->inStock = false;
    }

    /**
     * @throws \App\Entity\Exception\BookAlreadyStocked
     */
    public function checkIn() : void
    {
        if ($this->inStock) {
            throw Exception\BookAlreadyStocked::fromBook($this);
        }

        $this->inStock = true;
    }

    public function isInStock() : bool
    {
        return $this->inStock;
    }
}
