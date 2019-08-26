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
namespace DrinksAndCo\Agent\Model;

class InfomationRepository implements \DrinksAndCo\Agent\Api\InfomationRepositoryInterface
{
    /**
     * Cached instances
     * 
     * @var array
     */
    protected $_instances = [];

    /**
     * Infomation resource model
     * 
     * @var \DrinksAndCo\Agent\Model\ResourceModel\Infomation
     */
    protected $_resource;

    /**
     * Infomation collection factory
     * 
     * @var \DrinksAndCo\Agent\Model\ResourceModel\Infomation\CollectionFactory
     */
    protected $_infomationCollectionFactory;

    /**
     * Infomation interface factory
     * 
     * @var \DrinksAndCo\Agent\Api\Data\InfomationInterfaceFactory
     */
    protected $_infomationInterfaceFactory;

    /**
     * Data Object Helper
     * 
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $_dataObjectHelper;

    /**
     * Search result factory
     * 
     * @var \DrinksAndCo\Agent\Api\Data\InfomationSearchResultInterfaceFactory
     */
    protected $_searchResultsFactory;

    /**
     * constructor
     * 
     * @param \DrinksAndCo\Agent\Model\ResourceModel\Infomation $resource
     * @param \DrinksAndCo\Agent\Model\ResourceModel\Infomation\CollectionFactory $infomationCollectionFactory
     * @param \DrinksAndCo\Agent\Api\Data\InfomationInterfaceFactory $infomationInterfaceFactory
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \DrinksAndCo\Agent\Api\Data\InfomationSearchResultInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        \DrinksAndCo\Agent\Model\ResourceModel\Infomation $resource,
        \DrinksAndCo\Agent\Model\ResourceModel\Infomation\CollectionFactory $infomationCollectionFactory,
        \DrinksAndCo\Agent\Api\Data\InfomationInterfaceFactory $infomationInterfaceFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \DrinksAndCo\Agent\Api\Data\InfomationSearchResultInterfaceFactory $searchResultsFactory
    ) {
        $this->_resource                    = $resource;
        $this->_infomationCollectionFactory = $infomationCollectionFactory;
        $this->_infomationInterfaceFactory  = $infomationInterfaceFactory;
        $this->_dataObjectHelper            = $dataObjectHelper;
        $this->_searchResultsFactory        = $searchResultsFactory;
    }

    /**
     * Save Infomation.
     *
     * @param \DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation
     * @return \DrinksAndCo\Agent\Api\Data\InfomationInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation)
    {
        /** @var \DrinksAndCo\Agent\Api\Data\InfomationInterface|\Magento\Framework\Model\AbstractModel $infomation */
        try {
            $this->_resource->save($infomation);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__(
                'Could not save the Infomation: %1',
                $exception->getMessage()
            ));
        }
        return $infomation;
    }

    /**
     * Retrieve Infomation.
     *
     * @param int $infomationId
     * @return \DrinksAndCo\Agent\Api\Data\InfomationInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($infomationId)
    {
        if (!isset($this->_instances[$infomationId])) {
            /** @var \DrinksAndCo\Agent\Api\Data\InfomationInterface|\Magento\Framework\Model\AbstractModel $infomation */
            $infomation = $this->_infomationInterfaceFactory->create();
            $this->_resource->load($infomation, $infomationId);
            if (!$infomation->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('Requested Infomation doesn\'t exist'));
            }
            $this->_instances[$infomationId] = $infomation;
        }
        return $this->_instances[$infomationId];
    }

    /**
     * Retrieve Infomations matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \DrinksAndCo\Agent\Api\Data\InfomationSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \DrinksAndCo\Agent\Api\Data\InfomationSearchResultInterface $searchResults */
        $searchResults = $this->_searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \DrinksAndCo\Agent\Model\ResourceModel\Infomation\Collection $collection */
        $collection = $this->_infomationCollectionFactory->create();

        //Add filters from root filter group to the collection
        /** @var \Magento\Framework\Api\Search\FilterGroup $group */
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->_addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();
        /** @var \Magento\Framework\Api\SortOrder $sortOrder */
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                $collection->addOrder(
                    $field,
                    ($sortOrder->getDirection() == \Magento\Framework\Api\SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        } else {
            // set a default sorting order since this method is used constantly in many
            // different blocks
            $field = 'infomation_id';
            $collection->addOrder($field, 'ASC');
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        /** @var \DrinksAndCo\Agent\Api\Data\InfomationInterface[] $infomations */
        $infomations = [];
        /** @var \DrinksAndCo\Agent\Model\Infomation $infomation */
        foreach ($collection as $infomation) {
            /** @var \DrinksAndCo\Agent\Api\Data\InfomationInterface $infomationDataObject */
            $infomationDataObject = $this->_infomationInterfaceFactory->create();
            $this->_dataObjectHelper->populateWithArray(
                $infomationDataObject,
                $infomation->getData(),
                \DrinksAndCo\Agent\Api\Data\InfomationInterface::class
            );
            $infomations[] = $infomationDataObject;
        }
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($infomations);
    }

    /**
     * Delete Infomation.
     *
     * @param \DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\DrinksAndCo\Agent\Api\Data\InfomationInterface $infomation)
    {
        /** @var \DrinksAndCo\Agent\Api\Data\InfomationInterface|\Magento\Framework\Model\AbstractModel $infomation */
        $id = $infomation->getId();
        try {
            unset($this->_instances[$id]);
            $this->_resource->delete($infomation);
        } catch (\Magento\Framework\Exception\ValidatorException $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __('Unable to remove Infomation %1', $id)
            );
        }
        unset($this->_instances[$id]);
        return true;
    }

    /**
     * Delete Infomation by ID.
     *
     * @param int $infomationId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($infomationId)
    {
        $infomation = $this->getById($infomationId);
        return $this->delete($infomation);
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param \DrinksAndCo\Agent\Model\ResourceModel\Infomation\Collection $collection
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     */
    protected function _addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \DrinksAndCo\Agent\Model\ResourceModel\Infomation\Collection $collection
    ) {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = $filter->getField();
            $conditions[] = [$condition => $filter->getValue()];
        }
        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
        return $this;
    }
}
