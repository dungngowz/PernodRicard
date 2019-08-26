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
namespace DrinksAndCo\Agent\Model\Infomation;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * Loaded data cache
     * 
     * @var array
     */
    protected $_loadedData;

    /**
     * Data persistor
     * 
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $_dataPersistor;

    /**
     * constructor
     * 
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \DrinksAndCo\Agent\Model\ResourceModel\Infomation\CollectionFactory $collectionFactory
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \DrinksAndCo\Agent\Model\ResourceModel\Infomation\CollectionFactory $collectionFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->_dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \DrinksAndCo\Agent\Model\Infomation $infomation */
        foreach ($items as $infomation) {
            $this->_loadedData[$infomation->getId()] = $infomation->getData();

        }
        $data = $this->_dataPersistor->get('drinksandco_agent_infomation');
        if (!empty($data)) {
            $infomation = $this->collection->getNewEmptyItem();
            $infomation->setData($data);
            $this->_loadedData[$infomation->getId()] = $infomation->getData();
            $this->_dataPersistor->clear('drinksandco_agent_infomation');
        }
        return $this->_loadedData;
    }
}
