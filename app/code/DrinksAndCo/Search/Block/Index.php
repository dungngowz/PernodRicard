<?php
namespace DrinksAndCo\Search\Block;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_request;
    protected $_post;
    protected $_dataHelper;
    protected $post_per_page;
    protected $_commonHelper;
    protected $_imageBuilder;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        array $data = [],
        \DrinksAndCo\Search\Model\Data $post,
        \DrinksAndCo\Search\Helper\DataHelper $dataHelper,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \DrinksAndCo\Base\Helper\Common $commonHelper
    ) {
        $this->_request = $request;
        $this->_post = $post;
        $this->_dataHelper = $dataHelper;
        $this->post_per_page = 8 ;
        $this->_commonHelper = $commonHelper;
        $this->_imageBuilder = $imageBuilder;
        parent::__construct($context, $data);
    }

    public function _construct()
    {
        
        $keyword = $this->getDataSearch();
        parent::_construct();
        $post = $this->_post;
        $postCollection = $post->getCollection()
                                ->addFieldToFilter(array('title', 'short_content','content'),
                                    array(
                                        array('like'=>'%'.$keyword.'%'), 
                                        array('like'=>'%'.$keyword.'%'),
                                        array('like'=>'%'.$keyword.'%')
                                    ));

        $this->setCollection($postCollection);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Search'));
        
        $pageTitle = $this->getConfig('general_settings/title');
        $metaKeywords = $this->getConfig('general_settings/meta_keywords');
        $metaDescription = $this->getConfig('general_settings/meta_description');
        $this->pageConfig->addBodyClass('news-post-list');
        if ($pageTitle) {
            $this->pageConfig->getTitle()->set($pageTitle);
        }
        if ($metaKeywords) {
            $this->pageConfig->setKeywords($metaKeywords);
        }
        if ($metaDescription) {
            $this->pageConfig->setDescription($metaDescription);
        }
        if ($this->getCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'searc.post.list.pager'
            );
            $pager->setLimit($this->post_per_page)->setCollection(
                $this->getCollection()
            );
            $this->setChild('pager', $pager);
        }

        return parent::_prepareLayout();
    }

    public function getConfig($key, $default = '')
    {
        $result = $this->_dataHelper->getConfig($key);
        if (!$result) {
            return $default;
        }
        return $result;
    }
    
    public function getDataSearch(){
        $keyword = $this->_request->getParam('keyword');
        return $keyword;
    }

    public function setCollection($collection)
    {
        $this->_collection = $collection;
        return $this->_collection;
    }

    public function getCollection()
    {
        return $this->_collection;
    }

    public function getSearchCollection() {
        
    }

    public function getLoadMoreSearch(){
        return trim($this->getUrl('customsearch/loadmore/search?' . $this->getParamsSearch()), '/');
    }

    public function getParamsSearch(){
        $params = $this->_request->getParams();

        $queryString = [];
        if($params){
            foreach($params as $key => $item){
                if(!in_array($key, ['p', 'page'])){
                    $queryString[] = $key . '=' . $item;
                }
            }
        }

        return implode('&', $queryString);
    }

    public function getSearch(){
        $keyword = $this->getDataSearch();

        $collection = $this->getCollection()
                                        ->addFieldToFilter(array('title', 'short_content','content'),
                                        array(
                                            array('like'=>'%'.$keyword.'%'), 
                                            array('like'=>'%'.$keyword.'%'),
                                            array('like'=>'%'.$keyword.'%')
                                        ));

        $currentPage = $this->_request->getParam('p') ? $this->_request->getParam('p') : 1;
        $urlKey = $this->_request->getParam('path');
        $collection->setPageSize($this->post_per_page);
        $collection->setCurPage($currentPage);
        return $collection;
    }

    public function getThumbnailProduct($productId){
        $product = $this->_commonHelper->getDetailProductById($productId);
        if($product){
            return $this->getImage($product, 'product_small_image')->getImageUrl();
        }

        return "";
    }

    public function getImage($product, $imageId, $attributes = []){
        return $this->_imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }

}