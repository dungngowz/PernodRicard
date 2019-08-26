<?php

namespace DrinksAndCo\Blog\Model\Resource\Tag\Grid;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('DrinksAndCo\Blog\Model\Tag', 'DrinksAndCo\Blog\Model\Resource\Tag');
        $this->_map['fields']['tag_id'] = 'main_table.tag_id';
    }
}
