<?php
/**
 * @author MageHook <info@magehook.com>
 * @package MageHook_HookEventsSales
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MageHook\HookEventsSales\Model\Config\Source\Order;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\Order\Config as OrderConfig;

/**
 * Class State
 *
 * @package MageHook\HookEventsSales\Model\Config\Source\Order
 */
class State implements OptionSourceInterface
{
    /** @var OrderConfig $orderConfig */
    protected $orderConfig;

    /**
     * State constructor.
     *
     * @param OrderConfig $orderConfig
     */
    public function __construct(
        OrderConfig $orderConfig
    ) {
        $this->orderConfig = $orderConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [];
        $states = $this->orderConfig->getStates();

        foreach ($states as $key => $phrase) {
            $options[] = ['value' => $key, 'label' => $phrase];
        }

        return $options;
    }
}
