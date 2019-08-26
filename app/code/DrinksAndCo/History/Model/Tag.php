<?php

namespace DrinksAndCo\History\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use DrinksAndCo\History\Model\Resource\Tag as TagResource;
use DrinksAndCo\History\Model\Resource\Tag\Collection;
use DrinksAndCo\History\Helper\Data;

class Tag extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    protected $storeManager;
    protected $historyHelper;

    public function __construct(
        Context $context,
        Registry $registry,
        StoreManagerInterface $storeManager,
        TagResource $resource = null,
        Collection $resourceCollection = null,
        Data $historyHelper,
        array $data = []
    )
    {
        $this->storeManager = $storeManager;
        $this->historyHelper = $historyHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->_init('DrinksAndCo\History\Model\Resource\Tag');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}
