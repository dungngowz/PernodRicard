<?php

namespace DrinksAndCo\CustomizeOrder\Controller\Adminhtml\Order;

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

class Sms extends \Magento\Sales\Controller\Adminhtml\Order
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::email';

    /**
     * Notify user
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $order = $this->_initOrder();
        if ($order) {
            try {
                $this->orderManagement->notify($order->getEntityId());
                $this->messageManager->addSuccessMessage(__('You sent the order sms.'));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('We can\'t send the sms order right now.'));
                $this->logger->critical($e);
            }
            return $this->resultRedirectFactory->create()->setPath(
                'sales/order/view',
                [
                    'order_id' => $order->getEntityId()
                ]
            );
        }
        return $this->resultRedirectFactory->create()->setPath('sales/*/');
    }
}
