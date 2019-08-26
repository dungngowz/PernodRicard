<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace DrinksAndCo\Experience\Model\Config\Source;

/**
 * @api
 * @since 100.0.2
 */
class StyleType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Type 1')],
            ['value' => 2, 'label' => __('Type 2')]
        ];
    }

}
