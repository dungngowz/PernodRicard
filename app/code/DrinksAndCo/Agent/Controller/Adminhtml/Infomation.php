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
namespace DrinksAndCo\Agent\Controller\Adminhtml;

abstract class Infomation extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Infomation repository
     * 
     * @var \DrinksAndCo\Agent\Api\InfomationRepositoryInterface
     */
    protected $_infomationRepository;

    /**
     * Page factory
     * 
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * constructor
     * 
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \DrinksAndCo\Agent\Api\InfomationRepositoryInterface $infomationRepository
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \DrinksAndCo\Agent\Api\InfomationRepositoryInterface $infomationRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->_coreRegistry         = $coreRegistry;
        $this->_infomationRepository = $infomationRepository;
        $this->_resultPageFactory    = $resultPageFactory;
        parent::__construct($context);
    }
}
