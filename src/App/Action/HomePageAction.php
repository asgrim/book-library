<?php
declare(strict_types=1);

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class HomePageAction
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) : JsonResponse
    {
        return new JsonResponse([
            'welcome' => 'book-library works!',
        ]);
    }
}
