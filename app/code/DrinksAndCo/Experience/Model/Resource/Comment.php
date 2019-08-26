<?php

namespace DrinksAndCo\Experience\Model\Resource;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;

class Comment extends AbstractDb
{
    protected $storeManager;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        $connectionName = null
    )
    {
        $this->storeManager = $storeManager;
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('DrinksAndCo_Experience_comment', 'comment_id');
    }
}
