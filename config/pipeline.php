<?php
declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    // The error handler should be the first (most outer) middleware to catch
    // all Exceptions.
    $app->pipe(\Zend\Stratigility\Middleware\ErrorHandler::class);
    $app->pipe(\Zend\Expressive\Helper\ServerUrlMiddleware::class);

    $app->pipe(\App\Middleware\ErrorCatchingMiddleware::class);

    // Register the routing middleware in the middleware pipeline
    $app->pipe(\Zend\Expressive\Router\Middleware\PathBasedRoutingMiddleware::class);

    // The following handle routing failures for common conditions:
    // - method not allowed
    // - HEAD request but no routes answer that method
    // - OPTIONS request but no routes answer that method
    $app->pipe(\Zend\Expressive\Router\Middleware\MethodNotAllowedMiddleware::class);
    $app->pipe(\Zend\Expressive\Router\Middleware\ImplicitHeadMiddleware::class);
    $app->pipe(\Zend\Expressive\Router\Middleware\ImplicitOptionsMiddleware::class);

    // Seed the UrlHelper with the routing results:
    $app->pipe(\Zend\Expressive\Helper\UrlHelperMiddleware::class);

    // Wrap everything to auto-flush Doctrine (academic example, don't really do this in prod?)
    $app->pipe(\App\Middleware\FlushingMiddleware::class);

    // Session and auth setup
    $app->pipe(\PSR7Sessions\Storageless\Http\SessionMiddleware::class);
    $app->pipe(\App\Middleware\AuthenticationMiddleware::class);

    // Dispatches the request
    $app->pipe(\Zend\Expressive\Router\Middleware\DispatchMiddleware::class);

    // At this point, if no Response is returned by any middleware, the
    // NotFoundHandler kicks in; alternately, you can provide other fallback
    // middleware to execute.
    $app->pipe(\Zend\Expressive\Handler\NotFoundHandler::class);
};
