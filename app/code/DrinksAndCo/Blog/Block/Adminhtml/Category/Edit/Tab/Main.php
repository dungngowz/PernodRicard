<?php

namespace DrinksAndCo\Blog\Block\Adminhtml\Category\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use DrinksAndCo\Blog\Model\System\Config\Status;
use DrinksAndCo\Blog\Model\System\Config\Yesno;
use DrinksAndCo\Blog\Model\Source\ContentType;


class Main extends Generic implements TabInterface
{
    protected $_wysiwygConfig;
    protected $_status;
    protected $_yesno;
    protected $_systemStore;
    protected $_contentType;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        Status $status,
        Yesno $yesno,
        ContentType $contentType,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    )
    {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_status = $status;
        $this->_yesno = $yesno;
        $this->_systemStore = $systemStore;
        $this->_contentType = $contentType;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    public function getTabLabel()
    {
        return __('General');
    }

    public function getTabTitle()
    {
        return __('General');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_category');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('category_general_');
        $fieldset = $form->addFieldset('general_fieldset', ['legend' => __('General')]);
        if ($model->getId()) {
            $fieldset->addField('category_id', 'hidden', ['name' => 'category_id']);
        }

        /*$fieldset->addField(
            'content_type',
            'select',
            ['name' => 'category[content_type]', 'label' => __('Content Type'), 'title' => __('Content Type'), 'values' => $this->_contentType->toOptionArray()]
        );*/

        $fieldset->addField(
            'title',
            'text',
            ['name' => 'category[title]', 'label' => __('Title'), 'title' => __('Title'), 'required' => true]
        );
        $fieldset->addField(
            'url_key',
            'text',
            ['name' => 'category[url_key]', 'label' => __('URL Key'), 'title' => __('URL Key'), 'required' => false, 'class' => 'validate-identifier']
        );
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_id',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }
        $fieldset->addField(
            'status',
            'select',
            ['name' => 'category[status]', 'label' => __('Status'), 'title' => __('Status'), 'options' => $this->_status->toOptionArray()]
        );
        $fieldset->addField(
            'sort_order',
            'text',
            ['name' => 'category[sort_order]', 'label' => __('Sort Order'), 'title' => __('Sort Order'), 'required' => false]
        );
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
