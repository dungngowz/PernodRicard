<?php
namespace DrinksAndCo\CustomizeProduct\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    protected $_pageFactory;
    protected $_categoryCollectionFactory;
    protected $_productCollectionFactory;
    protected $_categoryHelper;
    protected $_categoryFactory;

	public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Helper\Category $categoryHelper
	){
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_categoryHelper = $categoryHelper;
        $this->_categoryFactory = $categoryFactory;
    }

    public function getCategoryCollection($levelBrand = false, $isActive = true, $level = false, $sortBy = false, $pageSize = false) {

        $collection = $this->_categoryCollectionFactory->create();

        $collection->addAttributeToSelect('*');
  
        // select categories by level brand
        if ($levelBrand) {
            $collection->addAttributeToFilter('dc_new_attribute', $levelBrand);
        }

        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }
  
        // select categories of certain level
        if ($level) {
            $collection->addLevelFilter($level);
        }
  
        // sort categories by some value
        if ($sortBy) {
            $collection->addOrderField($sortBy);
        }
  
        // set pagination
        if ($pageSize) {
            $collection->setPageSize($pageSize); 
        } 

        return $collection;
    }

    public function printCollectionCategory($collection){
        if($collection){
            foreach ($collection as $category){
                echo "<p>" . $category->getName() . "</p>";
            }
        }
    }

    public function getRecentProduct($catUrlKey = 'experiences', $pageSize = 5){
        $category = $this->_categoryFactory->create()->loadByAttribute('url_key', $catUrlKey);
        $collection = $category->getProductCollection()->addAttributeToSelect('*')
            ->setPageSize($pageSize)
            ->setOrder('created_at', 'desc');
        return $collection;
    }

    public function getRelatedProduct($productId, $catUrlKey = 'experiences', $pageSize = 4){
        $category = $this->_categoryFactory->create()->loadByAttribute('url_key', $catUrlKey);
        $collection = $category->getProductCollection()->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', array('neq' => $productId))
            ->setOrder('created_at', 'desc')
            ->setPageSize($pageSize);

        return ($collection->count() > 0) ? $collection : false;
    }
}
