<?php
declare(strict_types=1);

namespace AppTest\Action;

use App\Action\HomePageAction;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class HomePageActionTest extends \PHPUnit_Framework_TestCase
{
    public function testResponse() : void
    {
        $homePage = new HomePageAction();
        $response = $homePage(new ServerRequest(['/']), new Response(), function () {
        });

        self::assertInstanceOf(Response\JsonResponse::class, $response);
    }
}
