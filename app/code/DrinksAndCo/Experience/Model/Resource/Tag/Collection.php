<?php

namespace DrinksAndCo\Experience\Model\Resource\Tag;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('DrinksAndCo\Experience\Model\Tag', 'DrinksAndCo\Experience\Model\Resource\Tag');
    }
}
