<?php

namespace Cpsit\RequestLogger\Middleware;

use Cpsit\RequestLogger\Data\DataProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Cpsit\RequestLogger\Enum\LogLevel;
use Cpsit\RequestLogger\RequestMatcherInterface;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2024 Dirk Wenzel <wenzel@cps-it.de>
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
readonly class LoggerMiddleware implements MiddlewareInterface
{

    public function __construct(
        private LoggerInterface $logger, 
        private RequestMatcherInterface $requestMatcher,
        private DataProviderInterface $dataProvider
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if($this->requestMatcher->matches($request)) {

            $this->logger->log(
                $this->requestMatcher->getLevel()->value,
                $this->requestMatcher->getDescription(),
                $this->dataProvider->get($request)
            );
        }

        return $handler->handle($request);
    }
}
