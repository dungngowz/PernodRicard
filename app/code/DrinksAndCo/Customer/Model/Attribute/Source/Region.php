<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace DrinksAndCo\Customer\Model\Attribute\Source;

use Magento\Framework\Escaper;
use DrinksAndCo\Region\Model\RegionFactory as RegionFactory;

class Region extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * Escaper
     *
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * Constructor
     *
     * @param RegionFactory $regionFactory
     */
    public function __construct(RegionFactory $regionFactory, Escaper $escaper)
    {
        $this->regionFactory = $regionFactory;
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
        $collection = $this->regionFactory->create()
            ->getCollection()
            ->addFieldToFilter(
                'country_id',
                ['VN']
            )->addOrder(
                'default_name',
                'asc'
            );
        $result = [];
        $result[] = ['value' => ' ', 'label' => 'Select region...'];
        foreach ($collection as $brand) {
            $result[] = ['value' => $brand->getRegionId(), 'label' => $this->escaper->escapeHtml($brand->getDefaultName())];
        }
        return $result;
    }
}