<?php
declare(strict_types=1);

namespace App\Action;

use App\Entity\Exception\BookNotAvailable;
use App\Service\Book\Exception\BookNotFound;
use App\Service\Book\FindBookByUuidInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stratigility\MiddlewareInterface;

final class CheckOutAction implements MiddlewareInterface
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
            $book->checkOut();
        } catch (BookNotAvailable $bookNotAvailable) {
            return new JsonResponse(['info' => $bookNotAvailable->getMessage()], 423);
        }

        return new JsonResponse([
            'info' => sprintf('You have checked out %s', $book->getName()),
        ]);
    }
}
