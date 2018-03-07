<?php
declare(strict_types=1);

namespace App\Handler;

use App\Entity\Exception\BookAlreadyStocked;
use App\Service\Book\Exception\BookNotFound;
use App\Service\Book\FindBookByUuidInterface;
use App\Service\GetIncrementedCounterFromRequest;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response\JsonResponse;

final class CheckInHandler implements MiddlewareInterface
{
    /**
     * @var FindBookByUuidInterface
     */
    private $findBookByUuid;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(FindBookByUuidInterface $findBookByUuid, EntityManagerInterface $entityManager)
    {
        $this->findBookByUuid = $findBookByUuid;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler) : ResponseInterface
    {
        $counter = (new GetIncrementedCounterFromRequest())->__invoke($request);

        try {
            $book = $this->findBookByUuid->__invoke(Uuid::fromString($request->getAttribute('id')));
        } catch (BookNotFound $bookNotFound) {
            return new JsonResponse(['info' => $bookNotFound->getMessage(), 'counter' => $counter], 404);
        }

        try {
            $this->entityManager->transactional(function () use ($book) {
                $book->checkIn();
            });
        } catch (BookAlreadyStocked $bookAlreadyStocked) {
            return new JsonResponse(['info' => $bookAlreadyStocked->getMessage(), 'counter' => $counter], 423);
        }

        return new JsonResponse([
            'info' => sprintf('You have checked in %s', $book->getName()),
            'counter' => $counter,
        ]);
    }
}
