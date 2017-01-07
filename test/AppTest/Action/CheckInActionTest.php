<?php
declare(strict_types=1);

namespace AppTest\Action;

use App\Action\CheckInAction;
use App\Entity\Book;
use App\Service\Book\Exception\BookNotFound;
use App\Service\Book\FindBookByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Http\Middleware\DelegateInterface;
use PSR7Session\Http\SessionMiddleware;
use PSR7Session\Session\SessionInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

/**
 * @covers \App\Action\CheckInAction
 */
final class CheckInActionTest extends \PHPUnit_Framework_TestCase
{
    public function testResponseIs404WhenBookAlreadyStockedThrown()
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

        $action = new CheckInAction($findBookByUuid, $entityManager);

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

    public function testResponseIs423WhenBookAlreadyCheckedIn()
    {
        $book = Book::fromName('foo');

        $findBookByUuid = $this->createMock(FindBookByUuidInterface::class);
        $findBookByUuid->expects(self::once())->method('__invoke')->with($book->getId())->willReturn($book);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('transactional')->willReturnCallback('call_user_func');

        $action = new CheckInAction($findBookByUuid, $entityManager);

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

    public function testResponseIs200WhenBookSuccessfullyCheckedOut()
    {
        $book = Book::fromName('foo');
        $book->checkOut();

        $findBookByUuid = $this->createMock(FindBookByUuidInterface::class);
        $findBookByUuid->expects(self::once())->method('__invoke')->with($book->getId())->willReturn($book);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('transactional')->willReturnCallback('call_user_func');

        $action = new CheckInAction($findBookByUuid, $entityManager);

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
