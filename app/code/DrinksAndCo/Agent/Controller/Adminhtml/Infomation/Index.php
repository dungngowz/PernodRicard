<?php
/**
 * DrinksAndCo_Agent extension
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category  DrinksAndCo
 * @package   DrinksAndCo_Agent
 * @copyright Copyright (c) 2019
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */
namespace DrinksAndCo\Agent\Controller\Adminhtml\Infomation;

class Index extends \DrinksAndCo\Agent\Controller\Adminhtml\Infomation
{
    /**
     * Infomations list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_Agent::infomation');
        $resultPage->getConfig()->getTitle()->prepend(__('Infomations'));
        $resultPage->addBreadcrumb(__('Agent information'), __('Agent information'));
        $resultPage->addBreadcrumb(__('Infomations'), __('Infomations'));
        return $resultPage;
    }
}
