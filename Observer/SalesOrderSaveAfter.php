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
use MageHook\HookEventsSales\Registry\OrderEntityChangeRegistry;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface;

/**
 * Class SalesOrderSaveAfter
 *
 * @event sales_order_save_before
 * @package MageHook\HookEventsSales\Observer
 */
class SalesOrderSaveAfter implements ObserverInterface
{
    /** @var ManagerInterface $hookEventsManagerInterface */
    protected $hookEventsManagerInterface;

    /** @var OrderEntityChangeRegistry $orderEntityChangeRegistry */
    protected $orderEntityChangeRegistry;

    /** @var LoggerInterface $loggerInterface */
    protected $loggerInterface;

    /**
     * SalesOrderSaveBefore constructor.
     *
     * @param ManagerInterface          $hookEventsManagerInterface
     * @param OrderEntityChangeRegistry $orderEntityChangeRegistry
     * @param LoggerInterface           $loggerInterface
     */
    public function __construct(
        ManagerInterface $hookEventsManagerInterface,
        OrderEntityChangeRegistry $orderEntityChangeRegistry,
        LoggerInterface $loggerInterface
    ) {
        $this->hookEventsManagerInterface = $hookEventsManagerInterface;
        $this->orderEntityChangeRegistry = $orderEntityChangeRegistry;
        $this->loggerInterface = $loggerInterface;
    }

    /**
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer): void
    {
        /** @var OrderInterface $order */
        $order = $observer->getOrder();

        try {
            $this->orderEntityChangeRegistry->registerEntity('state');
            $this->orderEntityChangeRegistry->registerEntity('status');

            foreach ($this->orderEntityChangeRegistry->getEntities() as $entity) {
                $this->tryEvent($entity, $order);
            }
        } catch (AlreadyExistsException | NoSuchEntityException $exception) {
            $this->loggerInterface->critical($exception->getMessage());
        }
    }

    /**
     * @param string         $entity
     * @param OrderInterface $orderInterface
     *
     * @return bool
     * @throws NoSuchEntityException
     */
    public function tryEvent(string $entity, OrderInterface $orderInterface): bool
    {
        /** @var array $orig */
        $orig     = $orderInterface->getOrigData() ?? [];
        /** @var string $data */
        $current  = $orderInterface->getData($entity);
        /** @var string|null $previous */
        $previous = $orig[$entity] ?? null;

        if ($this->hasChange($current, $previous) && $this->orderEntityChangeRegistry->can($entity, $orderInterface)) {
            $this->hookEventsManagerInterface->fire('order_' . $entity . '_change', $orderInterface, [
                $this->getValidationData($entity, $orderInterface)
            ]);

            // Lock by registry object to avoid multiple calls in one run
            $this->orderEntityChangeRegistry->lock($entity, $orderInterface);
            return true;
        }

        return false;
    }

    /**
     * Check if the previous entity value has been changed based on the current.
     *
     * @param $previous
     * @param $current
     *
     * @return bool
     */
    public function hasChange($current, $previous): bool
    {
        return $previous === null || $previous !== $current;
    }

    /**
     * Can have a 'public' root key for sending along extra public data.
     *
     * @param string         $entity
     * @param OrderInterface $orderInterface
     *
     * @return array
     */
    public function getValidationData(string $entity, OrderInterface $orderInterface): array
    {
        return [
            $entity => $orderInterface->getData($entity),
            'customer_group' => $orderInterface->getCustomerGroupId()
        ];
    }
}
