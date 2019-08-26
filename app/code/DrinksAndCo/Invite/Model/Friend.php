<?php

/**
 * Friend.php
 *
 * @copyright Copyright © 1989 Staempfli. All rights reserved.
 * @author    dung.ngowz@gmail.com
 */

namespace DrinksAndCo\Invite\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Friend extends AbstractModel implements IdentityInterface
{
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'drinksandco_invite_friend';

    /**
     * @var string
     */
    protected $_cacheTag = 'drinksandco_invite_friend';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'drinksandco_invite_friend';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('DrinksAndCo\Invite\Model\ResourceModel\Friend');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Save from collection data
     *
     * @param array $data
     * @return $this|bool
     */
    public function saveCollection(array $data)
    {
        if (isset($data[$this->getId()])) {
            $this->addData($data[$this->getId()]);
            $this->getResource()->save($this);
        }
        return $this;
    }
}
