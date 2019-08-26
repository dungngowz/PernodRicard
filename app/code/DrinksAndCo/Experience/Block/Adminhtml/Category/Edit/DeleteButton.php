<?php
/**
 * DeleteButton
 *
 * @copyright Copyright © 1989 Staempfli. All rights reserved.
 * @author    dung.ngowz@gmail.com
 */
namespace DrinksAndCo\Experience\Block\Adminhtml\Category\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 */
class DeleteButton implements ButtonProviderInterface
{
    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * Registry
     *
     * @var Registry
     */
    protected $registry;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        Registry $registry
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [
            'label' => __('Delete'),
            'class' => 'delete',
            'id' => 'category-edit-delete-button',
            'data_attribute' => [
                'url' => $this->getDeleteUrl()
            ],
            'on_click' => 'deleteConfirm(\'' . __("Are you sure you want to do this?") . '\', \'' . $this->getDeleteUrl() . '\')',
            'sort_order' => 20,
        ];
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->urlBuilder->getUrl('*/*/delete', ['category_id' => $this->registry->registry('category_id')]);
    }
}
