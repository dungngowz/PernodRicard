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

abstract class MassAction extends \Magento\Backend\App\Action
{
    /**
     * Infomation repository
     * 
     * @var \DrinksAndCo\Agent\Api\InfomationRepositoryInterface
     */
    protected $_infomationRepository;

    /**
     * Mass Action filter
     * 
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $_filter;

    /**
     * Infomation collection factory
     * 
     * @var \DrinksAndCo\Agent\Model\ResourceModel\Infomation\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * Action success message
     * 
     * @var string
     */
    protected $_successMessage;

    /**
     * Action error message
     * 
     * @var string
     */
    protected $_errorMessage;

    /**
     * constructor
     * 
     * @param \Magento\Backend\App\Action\Context $context
     * @param \DrinksAndCo\Agent\Api\InfomationRepositoryInterface $infomationRepository
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \DrinksAndCo\Agent\Model\ResourceModel\Infomation\CollectionFactory $collectionFactory
     * @param string $successMessage
     * @param string $errorMessage
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \DrinksAndCo\Agent\Api\InfomationRepositoryInterface $infomationRepository,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \DrinksAndCo\Agent\Model\ResourceModel\Infomation\CollectionFactory $collectionFactory,
        $successMessage,
        $errorMessage
    ) {
        $this->_infomationRepository = $infomationRepository;
        $this->_filter               = $filter;
        $this->_collectionFactory    = $collectionFactory;
        $this->_successMessage       = $successMessage;
        $this->_errorMessage         = $errorMessage;
        parent::__construct($context);
    }

    /**
     * @param \DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation
     * @return mixed
     */
    abstract protected function _massAction(\DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation);

    /**
     * execute action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $collection = $this->_filter->getCollection($this->_collectionFactory->create());
            $collectionSize = $collection->getSize();
            foreach ($collection as $infomation) {
                $this->_massAction($infomation);
            }
            $this->messageManager->addSuccessMessage(__($this->_successMessage, $collectionSize));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, $this->_errorMessage);
        }
        $redirectResult = $this->resultRedirectFactory->create();
        $redirectResult->setPath('drinksandco_agent/*/index');
        return $redirectResult;
    }
}
