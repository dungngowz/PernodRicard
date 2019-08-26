<?php
    namespace DrinksAndCo\HelloWorld\Controller\Index;
    
    class TestA extends \Magento\Framework\App\Action\Action
    {
        protected $_pageFactory;
        
        public function __construct(
                                    \Magento\Framework\App\Action\Context $context,
                                    \Magento\Framework\View\Result\PageFactory $pageFactory)
        {
            $this->_pageFactory = $pageFactory;
            return parent::__construct($context);
        }
        
        public function execute()
        {
            echo "Hello https://178.128.103.32/helloworld/index/view";
            exit;
        }
    }

