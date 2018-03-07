<?php
declare(strict_types = 1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class ErrorCatchingMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler) : ResponseInterface
    {
        // Hide more of this stuff in production; maybe have a debug flag passed to the constructor here
        try {
            return $requestHandler->handle($request);
        } catch (\Throwable $throwable) {
            return new JsonResponse(
                [
                    'message' => $throwable->getMessage(),
                    'line' => $throwable->getLine(),
                    'file' => $throwable->getFile(),
                ],
                500
            );
        }
    }
}
