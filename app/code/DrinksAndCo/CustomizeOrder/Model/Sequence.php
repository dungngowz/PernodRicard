<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace DrinksAndCo\CustomizeOrder\Model;

use Magento\Framework\App\ResourceConnection as AppResource;
use Magento\Framework\DB\Sequence\SequenceInterface;

/**
 * Class Sequence represents sequence in logic
 *
 * @api
 * @since 100.0.2
 */
class Sequence implements SequenceInterface
{
    /**
     * Default pattern for Sequence
     */
    const DEFAULT_PATTERN  = "%s%'.06d%s";

    /**
     * @var string
     */
    private $lastIncrementId;

    /**
     * @var Meta
     */
    private $meta;

    /**
     * @var false|\Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $connection;

    /**
     * @var string
     */
    private $pattern;

    protected $customerSession;

    /**
     * @param Meta $meta
     * @param AppResource $resource
     * @param string $pattern
     */
    public function __construct(
        \Magento\SalesSequence\Model\Meta $meta,
        AppResource $resource,
        \Magento\Customer\Model\Session $customerSession,
        $pattern = self::DEFAULT_PATTERN
    ) {
        $this->meta = $meta;
        $this->connection = $resource->getConnection('sales');
        $this->pattern = $pattern;
        $this->customerSession = $customerSession;
    }

    /**
     * Retrieve current value
     *
     * @return string
     */
    public function getCurrentValue()
    {
        if (!isset($this->lastIncrementId)) {
            return null;
        }

        $currentCustomer = $this->customerSession->getCustomer();
        $groupId = $currentCustomer->getGroupId();
        $prefix = '';

        if($groupId == 3){//Staff
            $prefix = 's-';
        }else if($groupId == 4){//Friend And Family
            $prefix = 'f-';
        }else if($groupId == 2){//B2B
            $prefix = 'b-';
        }else if($groupId == 1){//Line Manager
            $prefix = 'l-';
        }

        return sprintf(
            $this->pattern,
            $prefix . $this->meta->getActiveProfile()->getPrefix(),
            $this->calculateCurrentValue(),
            $this->meta->getActiveProfile()->getSuffix()
        );
    }

    /**
     * Retrieve next value
     *
     * @return string
     */
    public function getNextValue()
    {
        $this->connection->insert($this->meta->getSequenceTable(), []);
        $this->lastIncrementId = $this->connection->lastInsertId($this->meta->getSequenceTable());
        return $this->getCurrentValue();
    }

    /**
     * Calculate current value depends on start value
     *
     * @return string
     */
    private function calculateCurrentValue()
    {
        return ($this->lastIncrementId - $this->meta->getActiveProfile()->getStartValue())
        * $this->meta->getActiveProfile()->getStep() + $this->meta->getActiveProfile()->getStartValue();
    }
}
