<?php

namespace DrinksAndCo\Blog\Model\Resource\Tag;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('DrinksAndCo\Blog\Model\Tag', 'DrinksAndCo\Blog\Model\Resource\Tag');
    }
}
