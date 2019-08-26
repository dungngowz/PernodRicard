<?php
/**
 * index.php
 *
 * @copyright Copyright © 1989 Staempfli. All rights reserved.
 * @author    dung.ngowz@gmail.com
 */
namespace DrinksAndCo\Invite\Controller\Adminhtml\Friend;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    
    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DrinksAndCo_Invite::friend');
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {   
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_Invite::friend');
        $resultPage->getConfig()->getTitle()->prepend(__('Friend'));

        return $resultPage;
    }
}
