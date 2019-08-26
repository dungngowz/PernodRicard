<?php

namespace DrinksAndCo\Invite\Model\Attribute\Source;

use Magento\Framework\Escaper;
use Magento\Customer\Model\CustomerFactory;

class Customer extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * Escaper
     *
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var CustomerFactory
     */
    protected $regionFactory;

    /**
     * Constructor
     *
     * @param CustomerFactory $customerFactory
     */
    public function __construct(CustomerFactory $customerFactory, Escaper $escaper)
    {
        $this->customerFactory = $customerFactory;
        $this->escaper = $escaper;
    }

    /**
     * Get all options
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options !== null) {
            return $this->_options;
        }

        $this->_options = $this->getAvailableRegions();

        return $this->_options;
    }

    /**
     * Prepare regions
     *
     * @return array
     */
    private function getAvailableRegions()
    {
        $collection = $this->customerFactory->create()->getCollection();
        $result = [];
        foreach ($collection as $item) {
            $result[] = ['value' => $item->getEntityId(), 'label' => $this->escaper->escapeHtml($item->getEmail())];
        }
        return $result;
    }
}