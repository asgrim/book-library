<?php
declare(strict_types=1);

namespace App\Action;

use App\Entity\Exception\BookAlreadyStocked;
use App\Service\Book\Exception\BookNotFound;
use App\Service\Book\FindBookByUuidInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stratigility\MiddlewareInterface;

final class CheckInAction implements MiddlewareInterface
{
    /**
     * @var FindBookByUuidInterface
     */
    private $findBookByUuid;

    public function __construct(FindBookByUuidInterface $findBookByUuid)
    {
        $this->findBookByUuid = $findBookByUuid;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) : JsonResponse
    {
        try {
            $book = $this->findBookByUuid->__invoke(Uuid::fromString($request->getAttribute('id')));
        } catch (BookNotFound $bookNotFound) {
            return new JsonResponse(['info' => $bookNotFound->getMessage()], 404);
        }

        try {
            $book->checkIn();
        } catch (BookAlreadyStocked $bookAlreadyStocked) {
            return new JsonResponse(['info' => $bookAlreadyStocked->getMessage()], 423);
        }

        return new JsonResponse([
            'info' => sprintf('You have checked in %s', $book->getId()),
        ]);
    }
}
