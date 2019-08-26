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

class Save extends \DrinksAndCo\Agent\Controller\Adminhtml\Infomation
{
    /**
     * Infomation factory
     * 
     * @var \DrinksAndCo\Agent\Api\Data\InfomationInterfaceFactory
     */
    protected $_infomationFactory;

    /**
     * Data Object Processor
     * 
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    protected $_dataObjectProcessor;

    /**
     * Data Object Helper
     * 
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $_dataObjectHelper;

    /**
     * Uploader pool
     * 
     * @var \DrinksAndCo\Agent\Model\UploaderPool
     */
    //protected $_uploaderPool;

    /**
     * Data Persistor
     * 
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $_dataPersistor;

    /**
     * constructor
     * 
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \DrinksAndCo\Agent\Api\InfomationRepositoryInterface $infomationRepository
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \DrinksAndCo\Agent\Api\Data\InfomationInterfaceFactory $infomationFactory
     * @param \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \DrinksAndCo\Agent\Model\UploaderPool $uploaderPool
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \DrinksAndCo\Agent\Api\InfomationRepositoryInterface $infomationRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \DrinksAndCo\Agent\Api\Data\InfomationInterfaceFactory $infomationFactory,
        \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        //\DrinksAndCo\Agent\Model\UploaderPool $uploaderPool,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->_infomationFactory   = $infomationFactory;
        $this->_dataObjectProcessor = $dataObjectProcessor;
        $this->_dataObjectHelper    = $dataObjectHelper;
        //$this->_uploaderPool        = $uploaderPool;
        $this->_dataPersistor       = $dataPersistor;
        parent::__construct($context, $coreRegistry, $infomationRepository, $resultPageFactory);
    }

    /**
     * run the action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var \DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation */
        $infomation = null;
        $postData = $this->getRequest()->getPostValue();
        $data = $postData;
        $id = !empty($data['infomation_id']) ? $data['infomation_id'] : null;
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            if ($id) {
                $infomation = $this->_infomationRepository->getById((int)$id);
            } else {
                unset($data['infomation_id']);
                $infomation = $this->_infomationFactory->create();
            }
            $this->_dataObjectHelper->populateWithArray($infomation, $data, \DrinksAndCo\Agent\Api\Data\InfomationInterface::class);
            $this->_infomationRepository->save($infomation);
            $this->messageManager->addSuccessMessage(__('You saved the Infomation'));
            $this->_dataPersistor->clear('drinksandco_agent_infomation');
            if ($this->getRequest()->getParam('back')) {
                $resultRedirect->setPath('drinksandco_agent/infomation/edit', ['infomation_id' => $infomation->getId()]);
            } else {
                $resultRedirect->setPath('drinksandco_agent/infomation');
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->_dataPersistor->set('drinksandco_agent_infomation', $postData);
            $resultRedirect->setPath('drinksandco_agent/infomation/edit', ['infomation_id' => $id]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('There was a problem saving the Infomation'));
            $this->_dataPersistor->set('drinksandco_agent_infomation', $postData);
            $resultRedirect->setPath('drinksandco_agent/infomation/edit', ['infomation_id' => $id]);
        }
        return $resultRedirect;
    }

    /**
     * @param string $type
     * @return \DrinksAndCo\Agent\Model\Uploader
     * @throws \Exception
     */
    protected function _getUploader($type)
    {
        //return $this->_uploaderPool->getUploader($type);
    }
}
