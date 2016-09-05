<?php
declare(strict_types=1);

namespace AppTest\Action;

use App\Action\CheckOutAction;
use App\Entity\Book;
use App\Service\Book\Exception\BookNotFound;
use App\Service\Book\FindBookByUuidInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

/**
 * @covers \App\Action\CheckOutAction
 */
final class CheckOutActionTest extends \PHPUnit_Framework_TestCase
{
    public function testResponseIs404WhenBookNotFoundThrown()
    {
        $uuid = Uuid::uuid4();

        $findBookByUuid = $this->createMock(FindBookByUuidInterface::class);
        $findBookByUuid
            ->expects(self::once())
            ->method('__invoke')
            ->with($uuid)
            ->willThrowException(BookNotFound::fromUuid($uuid));

        $action = new CheckOutAction($findBookByUuid);

        /** @var Response\JsonResponse $response */
        $response = $action(
            (new ServerRequest(['/']))->withAttribute('id', $uuid),
            new Response(),
            function () {
            }
        );

        self::assertInstanceOf(Response\JsonResponse::class, $response);
        self::assertSame(404, $response->getStatusCode());
    }

    public function testResponseIs423WhenBookAlreadyCheckedOut()
    {
        $book = Book::fromName('foo');
        $book->checkOut();

        $findBookByUuid = $this->createMock(FindBookByUuidInterface::class);
        $findBookByUuid->expects(self::once())->method('__invoke')->with($book->getId())->willReturn($book);

        $action = new CheckOutAction($findBookByUuid);

        /** @var Response\JsonResponse $response */
        $response = $action(
            (new ServerRequest(['/']))->withAttribute('id', $book->getId()),
            new Response(),
            function () {
            }
        );

        self::assertInstanceOf(Response\JsonResponse::class, $response);
        self::assertSame(423, $response->getStatusCode());
    }

    public function testResponseIs200WhenBookSuccessfullyCheckedOut()
    {
        $book = Book::fromName('foo');

        $findBookByUuid = $this->createMock(FindBookByUuidInterface::class);
        $findBookByUuid->expects(self::once())->method('__invoke')->with($book->getId())->willReturn($book);

        $action = new CheckOutAction($findBookByUuid);

        /** @var Response\JsonResponse $response */
        $response = $action(
            (new ServerRequest(['/']))->withAttribute('id', $book->getId()),
            new Response(),
            function () {
            }
        );

        self::assertInstanceOf(Response\JsonResponse::class, $response);
        self::assertSame(200, $response->getStatusCode());
    }
}
