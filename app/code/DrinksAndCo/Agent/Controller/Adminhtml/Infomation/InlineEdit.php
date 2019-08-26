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

class InlineEdit extends \DrinksAndCo\Agent\Controller\Adminhtml\Infomation
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
     * Data object processor
     * 
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    protected $_dataObjectProcessor;

    /**
     * Data object helper
     * 
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $_dataObjectHelper;

    /**
     * JSON Factory
     * 
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_jsonFactory;

    /**
     * Infomation resource model
     * 
     * @var \DrinksAndCo\Agent\Model\ResourceModel\Infomation
     */
    protected $_infomationResourceModel;

    /**
     * constructor
     * 
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \DrinksAndCo\Agent\Api\InfomationRepositoryInterface $infomationRepository
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \DrinksAndCo\Agent\Model\ResourceModel\Infomation $infomationResourceModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \DrinksAndCo\Agent\Api\InfomationRepositoryInterface $infomationRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \DrinksAndCo\Agent\Model\ResourceModel\Infomation $infomationResourceModel
    ) {
        $this->_dataObjectProcessor     = $dataObjectProcessor;
        $this->_dataObjectHelper        = $dataObjectHelper;
        $this->_jsonFactory             = $jsonFactory;
        $this->_infomationResourceModel = $infomationResourceModel;
        parent::__construct($context, $coreRegistry, $infomationRepository, $resultPageFactory);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->_jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $infomationId) {
            /** @var \DrinksAndCo\Agent\Model\Infomation|\DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation */
            $infomation = $this->_infomationRepository->getById((int)$infomationId);
            try {
                $infomationData = $postItems[$infomationId];
                $this->_dataObjectHelper->populateWithArray($infomation, $infomationData, \DrinksAndCo\Agent\Api\Data\InfomationInterface::class);
                $this->_infomationResourceModel->saveAttribute($infomation, array_keys($infomationData));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->_getErrorWithInfomationId($infomation, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->_getErrorWithInfomationId($infomation, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->_getErrorWithInfomationId(
                    $infomation,
                    __('Something went wrong while saving the Infomation.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add Infomation id to error message
     *
     * @param \DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation
     * @param string $errorText
     * @return string
     */
    protected function _getErrorWithInfomationId(\DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation, $errorText)
    {
        return '[Infomation ID: ' . $infomation->getId() . '] ' . $errorText;
    }
}
