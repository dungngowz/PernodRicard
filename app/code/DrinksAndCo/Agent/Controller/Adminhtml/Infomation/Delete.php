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

class Delete extends \DrinksAndCo\Agent\Controller\Adminhtml\Infomation
{
    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('infomation_id');
        if ($id) {
            try {
                $this->_infomationRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('The Infomation has been deleted.'));
                $resultRedirect->setPath('drinksandco_agent/*/');
                return $resultRedirect;
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The Infomation no longer exists.'));
                return $resultRedirect->setPath('drinksandco_agent/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('drinksandco_agent/infomation/edit', ['infomation_id' => $id]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was a problem deleting the Infomation'));
                return $resultRedirect->setPath('drinksandco_agent/infomation/edit', ['infomation_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a Infomation to delete.'));
        $resultRedirect->setPath('drinksandco_agent/*/');
        return $resultRedirect;
    }
}
