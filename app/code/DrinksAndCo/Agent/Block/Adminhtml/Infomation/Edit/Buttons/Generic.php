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
namespace DrinksAndCo\Agent\Block\Adminhtml\Infomation\Edit\Buttons;

class Generic
{
    /**
     * Widget Context
     * 
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $_context;

    /**
     * Infomation Repository
     * 
     * @var \DrinksAndCo\Agent\Api\InfomationRepositoryInterface
     */
    protected $_infomationRepository;

    /**
     * constructor
     * 
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \DrinksAndCo\Agent\Api\InfomationRepositoryInterface $infomationRepository
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \DrinksAndCo\Agent\Api\InfomationRepositoryInterface $infomationRepository
    ) {
        $this->_context              = $context;
        $this->_infomationRepository = $infomationRepository;
    }

    /**
     * Return Infomation ID
     *
     * @return int|null
     */
    public function getInfomationId()
    {
        try {
            return $this->_infomationRepository->getById(
                $this->_context->getRequest()->getParam('infomation_id')
            )->getId();
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->_context->getUrlBuilder()->getUrl($route, $params);
    }
}
