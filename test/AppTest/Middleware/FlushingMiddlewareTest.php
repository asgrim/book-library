<?php
declare(strict_types=1);

namespace AppTest\Middleware;

use App\Middleware\FlushingMiddleware;
use Doctrine\ORM\EntityManagerInterface;
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
        self::assertSame($expectedResponse, (new FlushingMiddleware($entityManager))->__invoke(
            new ServerRequest(),
            new Response(),
            function () use ($expectedResponse) {
                return $expectedResponse;
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
        self::assertSame($expectedResponse, (new FlushingMiddleware($entityManager))->__invoke(
            new ServerRequest(),
            new Response(),
            function () use ($expectedResponse) {
                return $expectedResponse;
            }
        ));
    }
}
