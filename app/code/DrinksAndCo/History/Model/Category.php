<?php

namespace DrinksAndCo\History\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use DrinksAndCo\History\Model\Resource\Category as CategoryResource;
use DrinksAndCo\History\Model\Resource\Category\Collection;
use DrinksAndCo\History\Helper\Data;

class Category extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    protected $storeManager;
    protected $historyHelper;

    public function __construct(
        Context $context,
        Registry $registry,
        StoreManagerInterface $storeManager,
        CategoryResource $resource = null,
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
        $this->_init('DrinksAndCo\History\Model\Resource\Category');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    
    public function addPostFilter($postId)
    {
        $this->getSelect()
            ->join(
                ['category_table' => $this->getTable('DrinksAndCo_History_category_post')],
                'main_table.category_id = category_table.category_id',
                []
            )
            ->where('category_table.post_id = ?', $postId);
        return $this;
    }
    
    public function getCategoryUrl()
    {
        $route = $this->historyHelper->getRoute();
        return $route . '/' . $this->getUrlKey();
    }
    
}
