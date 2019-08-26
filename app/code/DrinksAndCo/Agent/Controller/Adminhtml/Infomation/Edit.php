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

class Edit extends \DrinksAndCo\Agent\Controller\Adminhtml\Infomation
{
    /**
     * Initialize current Infomation and set it in the registry.
     *
     * @return int
     */
    protected function _initInfomation()
    {
        $infomationId = $this->getRequest()->getParam('infomation_id');
        $this->_coreRegistry->register(\DrinksAndCo\Agent\Controller\RegistryConstants::CURRENT_INFOMATION_ID, $infomationId);

        return $infomationId;
    }

    /**
     * Edit or create Infomation
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $infomationId = $this->_initInfomation();

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('DrinksAndCo_Agent::agent_infomation');
        $resultPage->getConfig()->getTitle()->prepend(__('Infomations'));
        $resultPage->addBreadcrumb(__('Agent information'), __('Agent information'));
        $resultPage->addBreadcrumb(__('Infomations'), __('Infomations'), $this->getUrl('drinksandco_agent/infomation'));

        if ($infomationId === null) {
            $resultPage->addBreadcrumb(__('New Infomation'), __('New Infomation'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Infomation'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Infomation'), __('Edit Infomation'));
            $resultPage->getConfig()->getTitle()->prepend(
                $this->_infomationRepository->getById($infomationId)->getName()
            );
        }
        return $resultPage;
    }
}
