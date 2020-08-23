<?php
/**
 * @author MageHook <info@magehook.com>
 * @package MageHook_HookEventsSales
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MageHook\HookEventsSales\Event\Options;

use MageHook\Hook\Event\Options\AbstractValidator;

/**
 * Class OrderValidator
 *
 * @package MageHook\HookEventsSales\Event\Options
 */
class OrderValidator extends AbstractValidator
{
    /**
     * @return bool
     */
    public function validateToPrice(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function validateFromPrice(): bool
    {
        return true;
    }
}
