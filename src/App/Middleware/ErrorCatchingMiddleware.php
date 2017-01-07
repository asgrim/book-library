<?php
declare(strict_types = 1);

namespace App\Middleware;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class ErrorCatchingMiddleware implements ServerMiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        // Hide more of this stuff in production; maybe have a debug flag passed to the constructor here
        try {
            return $delegate->process($request);
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
