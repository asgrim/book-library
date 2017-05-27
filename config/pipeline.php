<?php
/**
 * Expressive middleware pipeline
 */

/** @var \Zend\Expressive\Application $app */
$app->pipe(\Zend\Stratigility\Middleware\OriginalMessages::class);
$app->pipe(\Zend\Stratigility\Middleware\ErrorHandler::class);
$app->pipe(\Zend\Expressive\Helper\ServerUrlMiddleware::class);
$app->pipe(\App\Middleware\ErrorCatchingMiddleware::class);
$app->pipeRoutingMiddleware();
$app->pipe(\App\Middleware\FlushingMiddleware::class);
$app->pipe(\Zend\Expressive\Middleware\ImplicitHeadMiddleware::class);
$app->pipe(\Zend\Expressive\Middleware\ImplicitOptionsMiddleware::class);
$app->pipe(\Zend\Expressive\Helper\UrlHelperMiddleware::class);
$app->pipe(\PSR7Sessions\Storageless\Http\SessionMiddleware::class);
$app->pipe(\App\Middleware\AuthenticationMiddleware::class);
$app->pipeDispatchMiddleware();
$app->pipe(\Zend\Expressive\Middleware\NotFoundHandler::class);
