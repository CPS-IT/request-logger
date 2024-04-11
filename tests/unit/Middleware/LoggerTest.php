<?php
namespace Cpsit\RequestLogger\Tests\Unit;

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

use Cpsit\RequestLogger\Data\DataProviderInterface;
use Cpsit\RequestLogger\Enum\LogLevel;
use Psr\Http\Message\ServerRequestInterface;
use PHPUnit\Framework\TestCase;
use Cpsit\RequestLogger\Middleware\LoggerMiddleware;
use Cpsit\RequestLogger\RequestMatcherInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class LoggerTest extends TestCase
{
    protected ServerRequestInterface $request;
    protected RequestMatcherInterface $matcher;
    protected RequestHandlerInterface $handler;
    protected DataProviderInterface $dataProvider;
    protected LoggerInterface $logger;
    protected LoggerMiddleWare $subject;
    public function setUp(): void
    {

        $this->request = $this->getMockForAbstractClass(ServerRequestInterface::class);
        $this->handler = $this->getMockForAbstractClass(RequestHandlerInterface::class);
        $this->matcher = $this->getMockBuilder(RequestMatcherInterface::class)
            ->getMock();
        $this->dataProvider = $this->getMockBuilder(DataProviderInterface::class)
            ->getMock();
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $this->subject = new LoggerMiddleware(
            $this->logger,
            $this->matcher,
            $this->dataProvider
        );
    }

    public function testLoggerDoesntWriteLogForNonMatchingRequest(): void
    {
        $this->matcher->expects($this->once())->method('matches')
            ->with($this->request)
            ->willReturn(false);

        $this->logger->expects($this->never())->method('log');
        $this->dataProvider->expects($this->never())->method('get');
        $this->subject->process($this->request, $this->handler);
    }

    public function testLoggerWritesLogForMatchingRequest(): void
    {
        $this->matcher->expects($this->once())->method('matches')
            ->with($this->request)
            ->willReturn(true);

        $this->logger->expects($this->once())->method('log');
        $this->dataProvider->expects($this->once())->method('get');
        $this->matcher->expects($this->once())->method('getLevel')
            ->willReturn(LogLevel::INFO);
        $this->subject->process($this->request, $this->handler);
    }

}
