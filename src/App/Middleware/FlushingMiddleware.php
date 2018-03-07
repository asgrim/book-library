<?php
declare(strict_types = 1);

namespace App\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class FlushingMiddleware implements MiddlewareInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function process(Request $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        $response = $requestHandler->handle($request);

        if ($this->entityManager->isOpen()) {
            $this->entityManager->flush();
        }

        return $response;
    }
}
