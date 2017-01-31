<?php
declare(strict_types = 1);

namespace App\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;

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

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function __invoke(Request $request, Response $response, callable $out = null) : Response
    {
        if (null === $out) {
            throw new \InvalidArgumentException('Middleware passed to ' . __CLASS__ . ' must be piped');
        }

        $response = $out($request, $response);

        if ($this->entityManager->isOpen()) {
            $this->entityManager->flush();
        }

        return $response;
    }
}
