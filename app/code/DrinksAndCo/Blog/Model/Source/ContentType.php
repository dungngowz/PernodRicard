<?php

namespace DrinksAndCo\Blog\Model\Source;

use Magento\Framework\Escaper;
use Magento\Customer\Model\CustomerFactory;

class ContentType extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * Get all options
     * @return array
     */
    public function getAllOptions()
    {
        return [
            ['value' => 'news', 'label' => 'News'],
            ['value' => 'events', 'label' => 'Events'],
        ];
    }
}