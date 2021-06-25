<?php

declare(strict_types=1);

namespace Phpro\SoapClient\Middleware\WSICompliance;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Soap\Psr18Transport\HttpBinding\SoapActionDetector;

/**
 * @see http://www.ws-i.org/Profiles/BasicProfile-1.0-2004-04-16.html#R2744
 *
 * Fixes error:
 *
 *  WS-I Compliance failure (R2744):
 *  The value of the SOAPAction transport header must be double-quoted.
 */
class QuotedSoapActionMiddleware implements Plugin
{
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        $soapAction = SoapActionDetector::detectFromRequest($request);
        $soapAction = trim($soapAction ?? '', '"\'');

        return $next($request->withHeader('SOAPAction', '"'.$soapAction.'"'));
    }
}