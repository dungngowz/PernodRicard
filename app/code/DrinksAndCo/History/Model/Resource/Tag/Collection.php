<?php

namespace DrinksAndCo\History\Model\Resource\Tag;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('DrinksAndCo\History\Model\Tag', 'DrinksAndCo\History\Model\Resource\Tag');
    }
}
