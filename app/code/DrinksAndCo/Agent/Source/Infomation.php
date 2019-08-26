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
namespace DrinksAndCo\Agent\Source;

class Infomation implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Infomation repository
     * 
     * @var \DrinksAndCo\Agent\Api\InfomationRepositoryInterface
     */
    protected $_infomationRepository;

    /**
     * Search Criteria Builder
     * 
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;

    /**
     * Filter Builder
     * 
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $_filterBuilder;

    /**
     * Options
     * 
     * @var array
     */
    protected $_options;

    /**
     * constructor
     * 
     * @param \DrinksAndCo\Agent\Api\InfomationRepositoryInterface $infomationRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     */
    public function __construct(
        \DrinksAndCo\Agent\Api\InfomationRepositoryInterface $infomationRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder
    ) {
        $this->_infomationRepository  = $infomationRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_filterBuilder         = $filterBuilder;
    }

    /**
     * Retrieve all Infomations as an option array
     *
     * @return array
     * @throws StateException
     */
    public function getAllOptions()
    {
        if (empty($this->_options)) {
            $options = [];
            $searchCriteria = $this->_searchCriteriaBuilder->create();
            $searchResults = $this->_infomationRepository->getList($searchCriteria);
            foreach ($searchResults->getItems() as $infomation) {
                $options[] = [
                    'value' => $infomation->getInfomationId(),
                    'label' => $infomation->getName(),
                ];
            }
            $this->_options = $options;
        }

        return $this->_options;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }
}
