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
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory as StatusCollectionFactory;

/**
 * Class Status
 *
 * @package MageHook\HookEventsSales\Model\Config\Source\Order
 */
class Status implements OptionSourceInterface
{
    /** @var StatusCollectionFactory $statusCollectionFactory */
    protected $statusCollectionFactory;

    /**
     * Status constructor.
     *
     * @param StatusCollectionFactory $statusCollectionFactory
     */
    public function __construct(
        StatusCollectionFactory $statusCollectionFactory
    ) {
        $this->statusCollectionFactory = $statusCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return $this->statusCollectionFactory->create()->toOptionArray();
    }
}
