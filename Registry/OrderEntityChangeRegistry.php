<?php
/**
 * @author MageHook <info@magehook.com>
 * @package MageHook_HookEventsSales
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MageHook\HookEventsSales\Registry;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Class OrderEntityChangeRegistry
 *
 * @package MageHook\HookEventsSales\Registry
 */
class OrderEntityChangeRegistry
{
    /** @var array $entries */
    private $entries;

    /**
     * OrderEntityChangeRegistry constructor.
     *
     * @param array $entries
     */
    public function __construct(
        array $entries = []
    ) {
        $this->entries = $entries;
    }

    /**
     * @param string $entity
     *
     * @return $this
     * @throws AlreadyExistsException
     */
    public function registerEntity(string $entity): self
    {
        if (isset($this->entries[$entity])) {
            throw new AlreadyExistsException(__('Entity %1 already exists', $entity));
        }

        $this->entries[$entity] = [];
        return $this;
    }

    /**
     * @return array
     */
    public function getEntities(): array
    {
        return \array_keys($this->entries);
    }

    /**
     * @param string $entity
     *
     * @return bool
     */
    public function entityExists(string $entity): bool
    {
        return isset($this->entries[$entity]);
    }

    /**
     * @param string         $entity
     * @param OrderInterface $orderInterface
     *
     * @return bool
     * @throws NoSuchEntityException
     */
    public function can(string $entity, OrderInterface $orderInterface): bool
    {
        if (!$this->entityExists($entity)) {
            throw new NoSuchEntityException(__('Entity %1 could was not registered', $entity));
        }

        return !isset($this->entries[$entity][$orderInterface->getEntityId()]);
    }

    /**
     * @param string         $entity
     * @param OrderInterface $orderInterface
     *
     * @return bool
     * @throws NoSuchEntityException
     */
    public function cannot(string $entity, OrderInterface $orderInterface): bool
    {
        return !$this->can($entity, $orderInterface);
    }

    /**
     * @param string         $entity
     * @param OrderInterface $orderInterface
     *
     * @return $this
     * @throws NoSuchEntityException
     */
    public function lock(string $entity, OrderInterface $orderInterface): self
    {
        if (!$this->entityExists($entity)) {
            throw new NoSuchEntityException(__('Entity %1 could was not registered', $entity));
        }

        $this->entries[$entity][$orderInterface->getEntityId()] = $orderInterface;
        return $this;
    }
}
