<?php
/**
 * Collection.php
 *
 * @copyright Copyright © 1989 Staempfli. All rights reserved.
 * @author    dung.ngowz@gmail.com
 */
namespace DrinksAndCo\Experience\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'post_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('DrinksAndCo\Experience\Model\Post', 'DrinksAndCo\Experience\Model\ResourceModel\Post');
    }
}
