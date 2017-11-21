<?php
declare(strict_types=1);

namespace AppTest\Action;

use App\Action\CheckOutAction;
use App\Entity\Book;
use App\Service\Book\Exception\BookNotFound;
use App\Service\Book\FindBookByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\SessionInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

/**
 * @covers \App\Action\CheckOutAction
 */
final class CheckOutActionTest extends TestCase
{
    public function testResponseIs404WhenBookNotFoundThrown() : void
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

        $action = new CheckOutAction($findBookByUuid, $entityManager);

        /** @var Response\JsonResponse $response */
        $response = $action->process(
            (new ServerRequest(['/']))
                ->withAttribute('id', $uuid)
                ->withAttribute(SessionMiddleware::SESSION_ATTRIBUTE, $this->createMock(SessionInterface::class)),
            $this->createMock(DelegateInterface::class)
        );

        self::assertInstanceOf(Response\JsonResponse::class, $response);
        self::assertSame(404, $response->getStatusCode());
    }

    public function testResponseIs423WhenBookAlreadyCheckedOut() : void
    {
        $book = Book::fromName('foo');
        $book->checkOut();

        $findBookByUuid = $this->createMock(FindBookByUuidInterface::class);
        $findBookByUuid->expects(self::once())->method('__invoke')->with($book->getId())->willReturn($book);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('transactional')->willReturnCallback('call_user_func');

        $action = new CheckOutAction($findBookByUuid, $entityManager);

        /** @var Response\JsonResponse $response */
        $response = $action->process(
            (new ServerRequest(['/']))
                ->withAttribute('id', $book->getId())
                ->withAttribute(SessionMiddleware::SESSION_ATTRIBUTE, $this->createMock(SessionInterface::class)),
            $this->createMock(DelegateInterface::class)
        );

        self::assertInstanceOf(Response\JsonResponse::class, $response);
        self::assertSame(423, $response->getStatusCode());
    }

    public function testResponseIs200WhenBookSuccessfullyCheckedOut() : void
    {
        $book = Book::fromName('foo');

        $findBookByUuid = $this->createMock(FindBookByUuidInterface::class);
        $findBookByUuid->expects(self::once())->method('__invoke')->with($book->getId())->willReturn($book);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('transactional')->willReturnCallback('call_user_func');

        $action = new CheckOutAction($findBookByUuid, $entityManager);

        /** @var Response\JsonResponse $response */
        $response = $action->process(
            (new ServerRequest(['/']))
                ->withAttribute('id', $book->getId())
                ->withAttribute(SessionMiddleware::SESSION_ATTRIBUTE, $this->createMock(SessionInterface::class)),
            $this->createMock(DelegateInterface::class)
        );

        self::assertInstanceOf(Response\JsonResponse::class, $response);
        self::assertSame(200, $response->getStatusCode());
    }
}
