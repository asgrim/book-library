<?php
declare(strict_types = 1);

namespace App\Middleware;

use Dflydev\FigCookies\SetCookie;
use Interop\Container\ContainerInterface;
use Lcobucci\JWT\Parser;
use PSR7Session\Http\SessionMiddleware;
use PSR7Session\Time\SystemCurrentTime;
use Zend\ServiceManager\Factory\FactoryInterface;
use Lcobucci\JWT\Signer;

/**
 * @codeCoverageIgnore
 */
final class SessionMiddlewareFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $symmetricKey = 'super-secure-key-you-should-not-store-this-key-in-git';
        $expirationTime = 1200; // 20 minutes

        return new SessionMiddleware(
            new Signer\Hmac\Sha256(),
            $symmetricKey,
            $symmetricKey,
            SetCookie::create(SessionMiddleware::DEFAULT_COOKIE)
                ->withSecure(false) // false on purpose, unless you have https locally
                ->withHttpOnly(true)
                ->withPath('/'),
            new Parser(),
            $expirationTime,
            new SystemCurrentTime()
        );
    }
}
