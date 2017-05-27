<?php
declare(strict_types=1);

namespace AppTest\Middleware;

use App\Middleware\FlushingMiddleware;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

/**
 * @covers \App\Middleware\FlushingMiddleware
 */
final class FlushingMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function testEntityManagerIsFlushedWhenOpen()
    {
        /** @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('isOpen')->willReturn(true);
        $entityManager->expects(self::once())->method('flush');

        $expectedResponse = new Response();
        self::assertSame($expectedResponse, (new FlushingMiddleware($entityManager))->process(
            new ServerRequest(),
            new class ($expectedResponse) implements DelegateInterface {
                private $fakeResponse;
                public function __construct(ResponseInterface $fakeResponse)
                {
                    $this->fakeResponse = $fakeResponse;
                }

                public function process(ServerRequestInterface $request)
                {
                    return $this->fakeResponse;
                }
            }
        ));
    }

    public function testNothingHappensWhenEntityManagerIsClosed()
    {
        /** @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('isOpen')->willReturn(false);
        $entityManager->expects(self::never())->method('flush');

        $expectedResponse = new Response();
        self::assertSame($expectedResponse, (new FlushingMiddleware($entityManager))->process(
            new ServerRequest(),
            new class ($expectedResponse) implements DelegateInterface {
                private $fakeResponse;
                public function __construct(ResponseInterface $fakeResponse)
                {
                    $this->fakeResponse = $fakeResponse;
                }

                public function process(ServerRequestInterface $request)
                {
                    return $this->fakeResponse;
                }
            }
        ));
    }
}
