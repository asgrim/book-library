<?php
declare(strict_types=1);

namespace App\Action;

use App\Entity\Exception\BookNotAvailable;
use App\Service\Book\Exception\BookNotFound;
use App\Service\Book\FindBookByUuidInterface;
use App\Service\GetIncrementedCounterFromRequest;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response\JsonResponse;

final class CheckOutAction implements MiddlewareInterface
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

    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : JsonResponse
    {
        $counter = (new GetIncrementedCounterFromRequest())->__invoke($request);

        try {
            $book = $this->findBookByUuid->__invoke(Uuid::fromString($request->getAttribute('id')));
        } catch (BookNotFound $bookNotFound) {
            return new JsonResponse(['info' => $bookNotFound->getMessage(), 'counter' => $counter], 404);
        }

        try {
            $this->entityManager->transactional(function () use ($book) {
                $book->checkOut();
            });
        } catch (BookNotAvailable $bookNotAvailable) {
            return new JsonResponse(['info' => $bookNotAvailable->getMessage(), 'counter' => $counter], 423);
        }

        return new JsonResponse([
            'info' => sprintf('You have checked out %s', $book->getName()),
            'counter' => $counter,
        ]);
    }
}
