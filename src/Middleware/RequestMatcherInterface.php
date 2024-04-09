<?php

namespace Cpsit\RequestLogger\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Cpsit\RequestLogger\Enum\LogLevel;

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
interface RequestMatcherInterface
{
    /**
     * Returns true if the request matches the rules of this instance
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function matches(ServerRequestInterface $request): bool;

    public function getLevel():LogLevel;

    /**
     * Returns a description of this instance. E.g. its matching rules.
     * @return string
     */
    public function getDescription(): string;
}
