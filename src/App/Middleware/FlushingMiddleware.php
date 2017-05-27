<?php
declare(strict_types = 1);

namespace App\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

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

    public function process(Request $request, DelegateInterface $delegate)
    {
        $response = $delegate->process($request);

        if ($this->entityManager->isOpen()) {
            $this->entityManager->flush();
        }

        return $response;
    }
}
