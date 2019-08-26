<?php

/**
 * Friend.php
 *
 * @copyright Copyright © 1989 Staempfli. All rights reserved.
 * @author    dung.ngowz@gmail.com
 */
namespace DrinksAndCo\Invite\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Friend extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('drinksandco_invite_friend', 'entity_id');
    }
}
