<?php
declare(strict_types = 1);

namespace App\Service;

use Psr\Http\Message\RequestInterface as Request;
use PSR7Session\Http\SessionMiddleware;

final class GetIncrementedCounterFromRequest
{
    public function __invoke(Request $request)
    {
        /* @var \PSR7Session\Session\DefaultSessionData $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $session->set('counter', $session->get('counter', 0) + 1);
        return $session->get('counter');
    }
}
