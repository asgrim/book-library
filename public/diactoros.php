<?php
declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

$server = \Zend\Diactoros\Server::createServer(
    function ($request, $response, $done) {
        return $response->getBody()->write('hello world');
    },
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$server->listen();
