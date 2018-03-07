<?php
declare(strict_types = 1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

final class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler) : Response
    {
        $queryParams = $request->getQueryParams();

        // DO NOT DO THIS IN REAL LIFE
        // It's really not secure ;)
        if (!array_key_exists('authenticated', $queryParams) || $queryParams['authenticated'] !== '1') {
            return new JsonResponse(['error' => 'You are not authenticated.'], 403);
        }

        return $requestHandler->handle($request);
    }
}
