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

use Magento\Sales\Api\Data\OrderInterface;

/**
 * Class OrderStatusChangeValidator
 *
 * @package MageHook\HookEventsSales\Event\Options
 */
class OrderStatusChangeValidator extends OrderValidator
{
    /**
     * @return bool
     */
    public function validateStatusChange(): bool
    {
        /** @var OrderInterface $order */
        $order = $this->getResource();

        $validations = [
            'statuses' => false
        ];

        if ($this->hasStatuses()) {
            $statuses = \in_array($order->getStatus(), $this->getStatuses(), true);
            $validations['statuses'] = $statuses;

            if ($statuses && $this->hasStates()) {
                $validations['state'] = \in_array($order->getState(), $this->getStates(), true);
            }
        }

        foreach ($validations as $validation) {
            if ($validation === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get required status options.
     *
     * @return array
     */
    public function getStatuses(): array
    {
        $statuses = $this->getOptions()->getStatuses();
        return empty($statuses) ? [] : $statuses;
    }

    /**
     * @return bool
     */
    public function hasStatuses(): bool
    {
        return !empty($this->getStatuses());
    }

    /**
     * Get optional state options.
     *
     * @return array
     */
    public function getStates(): array
    {
        $states = $this->getOptions()->getStates();
        return empty($states) ? [] : $states;
    }

    /**
     * @return bool
     */
    public function hasStates(): bool
    {
        return !empty($this->getStates());
    }
}
