<?php

/**
 * Post.php
 *
 * @copyright Copyright © 1989 Staempfli. All rights reserved.
 * @author    dung.ngowz@gmail.com
 */
namespace DrinksAndCo\Experience\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Post extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('drinksandco_experience_post', 'post_id');
    }
}
