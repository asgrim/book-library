<?php
declare(strict_types = 1);

namespace App\Service;

use Psr\Http\Message\ServerRequestInterface as Request;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\DefaultSessionData;

final class GetIncrementedCounterFromRequest
{
    public function __invoke(Request $request)
    {
        /* @var DefaultSessionData $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $session->set('counter', $session->get('counter', 0) + 1);
        return $session->get('counter');
    }
}
