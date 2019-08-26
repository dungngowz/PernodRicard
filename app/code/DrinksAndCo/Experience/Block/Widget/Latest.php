<?php

namespace DrinksAndCo\Experience\Block\Widget;

class Latest extends AbstractWidget
{
    protected $_post;
    protected $_coreRegistry = null;
    protected $_experienceHelper;
    protected $_storeManager;
    protected $_template = 'widget/lastest.phtml';
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \DrinksAndCo\Experience\Helper\Data $experienceHelper,
        \DrinksAndCo\Experience\Model\Post $post,
        array $data = []
    )
    {
        $this->_post = $post;
        $this->_experienceHelper = $experienceHelper;
        $this->_coreRegistry = $registry;
        $this->_storeManager = $context->getStoreManager();
        parent::__construct($context, $experienceHelper);
    }


    public function getPostCollection()
    {
        $post = $this->_post;
        $postCollection = $post->getCollection()
            ->addFieldToFilter('status', 1)
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('created_at', 'DESC');
		if ($this->getConfig('category')){
            $postCollection->addCategoryFilter($this->getConfig('category'));
        }
        $postCollection->getSelect()->limit($this->getConfig('post_count'));
        return $postCollection;
    }
    public function getDataSlider(){
        $options = array(
            'item_md' => ($this->getConfig('max_item') != '')? $this->getConfig('max_item') :4,
            'item_sm' => ($this->getConfig('medium_item') != '')? $this->getConfig('medium_item') :2,
            'item_xs' => ($this->getConfig('min_item') != '')? $this->getConfig('min_item') :1,
            // 'center' => ($this->getConfig('center') == 1)? true :false,
            'dots' => ($this->getConfig('show_dots') == 0)? false :true,
            'nav'=> ($this->getConfig('show_nav') == 0)? false :true,
            'loop'=> ($this->getConfig('loop') == 0)? false :true,
            'autoplay' => ($this->getConfig('autoplay') == 0)? false :true,
            'autoplaySpeed'=> ($this->getConfig('autoplay_speed') != '')? $this->getConfig('autoplay_speed') :3000,
        );
        return json_encode($options);
    }
    public function getRow(){
        return ($this->getConfig('row'))? $this->getConfig('row') : 1;
    }    

}
