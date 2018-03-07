<?php
declare(strict_types=1);

namespace AppTest\Middleware;

use App\Middleware\FlushingMiddleware;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

/**
 * @covers \App\Middleware\FlushingMiddleware
 */
final class FlushingMiddlewareTest extends TestCase
{
    public function testEntityManagerIsFlushedWhenOpen() : void
    {
        /** @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('isOpen')->willReturn(true);
        $entityManager->expects(self::once())->method('flush');

        $expectedResponse = new Response();
        self::assertSame($expectedResponse, (new FlushingMiddleware($entityManager))->process(
            new ServerRequest(),
            new class ($expectedResponse) implements RequestHandlerInterface {
                private $fakeResponse;
                public function __construct(ResponseInterface $fakeResponse)
                {
                    $this->fakeResponse = $fakeResponse;
                }

                public function handle(ServerRequestInterface $request): ResponseInterface
                {
                    return $this->fakeResponse;
                }
            }
        ));
    }

    public function testNothingHappensWhenEntityManagerIsClosed() : void
    {
        /** @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('isOpen')->willReturn(false);
        $entityManager->expects(self::never())->method('flush');

        $expectedResponse = new Response();
        self::assertSame($expectedResponse, (new FlushingMiddleware($entityManager))->process(
            new ServerRequest(),
            new class ($expectedResponse) implements RequestHandlerInterface {
                private $fakeResponse;
                public function __construct(ResponseInterface $fakeResponse)
                {
                    $this->fakeResponse = $fakeResponse;
                }

                public function handle(ServerRequestInterface $request): ResponseInterface
                {
                    return $this->fakeResponse;
                }
            }
        ));
    }
}
