<?php
/**
 * @author MageHook <info@magehook.com>
 * @package MageHook_HookEventsSales
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MageHook\HookEventsSales\Observer;

use MageHook\Hook\ManagerInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class SalesOrderInvoicePay
 *
 * @package MageHook\HookEventsSales\Observer
 */
class SalesOrderInvoicePay implements ObserverInterface
{
    /** @var ManagerInterface $hookEventsManagerInterface */
    protected $hookEventsManagerInterface;

    /**
     * SalesOrderInvoicePay constructor.
     *
     * @param ManagerInterface $hookEventsManagerInterface
     */
    public function __construct(
        ManagerInterface $hookEventsManagerInterface
    ) {
        $this->hookEventsManagerInterface = $hookEventsManagerInterface;
    }

    /**
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer): void
    {
        $this->hookEventsManagerInterface->fire('new_order_invoice', $observer->getInvoice());
    }
}