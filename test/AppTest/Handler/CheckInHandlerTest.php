<?php
declare(strict_types=1);

namespace AppTest\Handler;

use App\Handler\CheckInHandler;
use App\Entity\Book;
use App\Service\Book\Exception\BookNotFound;
use App\Service\Book\FindBookByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

/**
 * @covers \App\Handler\CheckInHandler
 */
final class CheckInHandlerTest extends TestCase
{
    public function testResponseIs404WhenBookAlreadyStockedThrown() : void
    {
        $uuid = Uuid::uuid4();

        $findBookByUuid = $this->createMock(FindBookByUuidInterface::class);
        $findBookByUuid
            ->expects(self::once())
            ->method('__invoke')
            ->with($uuid)
            ->willThrowException(BookNotFound::fromUuid($uuid));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::never())->method('transactional');

        $handler = new CheckInHandler($findBookByUuid, $entityManager);

        /** @var Response\JsonResponse $response */
        $response = $handler->process(
            (new ServerRequest(['/']))
                ->withAttribute('id', $uuid)
                ->withAttribute(SessionMiddleware::SESSION_ATTRIBUTE, $this->createMock(SessionInterface::class)),
            $this->createMock(RequestHandlerInterface::class)
        );

        self::assertInstanceOf(Response\JsonResponse::class, $response);
        self::assertSame(404, $response->getStatusCode());
    }

    public function testResponseIs423WhenBookAlreadyCheckedIn() : void
    {
        $book = Book::fromName('foo');

        $findBookByUuid = $this->createMock(FindBookByUuidInterface::class);
        $findBookByUuid->expects(self::once())->method('__invoke')->with($book->getId())->willReturn($book);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('transactional')->willReturnCallback('call_user_func');

        $handler = new CheckInHandler($findBookByUuid, $entityManager);

        /** @var Response\JsonResponse $response */
        $response = $handler->process(
            (new ServerRequest(['/']))
                ->withAttribute('id', $book->getId())
                ->withAttribute(SessionMiddleware::SESSION_ATTRIBUTE, $this->createMock(SessionInterface::class)),
            $this->createMock(RequestHandlerInterface::class)
        );

        self::assertInstanceOf(Response\JsonResponse::class, $response);
        self::assertSame(423, $response->getStatusCode());
    }

    public function testResponseIs200WhenBookSuccessfullyCheckedOut() : void
    {
        $book = Book::fromName('foo');
        $book->checkOut();

        $findBookByUuid = $this->createMock(FindBookByUuidInterface::class);
        $findBookByUuid->expects(self::once())->method('__invoke')->with($book->getId())->willReturn($book);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('transactional')->willReturnCallback('call_user_func');

        $handler = new CheckInHandler($findBookByUuid, $entityManager);

        /** @var Response\JsonResponse $response */
        $response = $handler->process(
            (new ServerRequest(['/']))
                ->withAttribute('id', $book->getId())
                ->withAttribute(SessionMiddleware::SESSION_ATTRIBUTE, $this->createMock(SessionInterface::class)),
            $this->createMock(RequestHandlerInterface::class)
        );

        self::assertInstanceOf(Response\JsonResponse::class, $response);
        self::assertSame(200, $response->getStatusCode());
    }
}
