<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace DrinksAndCo\News\Model\Config\Source;

class Category implements \Magento\Framework\Option\ArrayInterface
{
    protected $_cateCollection;
    public function __construct(
        \DrinksAndCo\News\Model\Resource\Category\Collection $cateCollectiton
    )
    {
        $this->_cateCollection = $cateCollectiton;
    }

    public function toOptionArray()
    {
        $collection = $this->_cateCollection->addFieldToFilter('status', 1);
        $arr = [];
        foreach ($collection as $categories){
            $arr[] = [
                'value' => $categories->getId(),
                'label' => $categories->getTitle()
            ];
        }

       return $arr;
    }

}
