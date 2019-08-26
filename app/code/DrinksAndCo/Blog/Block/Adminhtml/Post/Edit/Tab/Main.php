<?php

namespace DrinksAndCo\Blog\Block\Adminhtml\Post\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use DrinksAndCo\Blog\Model\System\Config\Status;
use DrinksAndCo\Blog\Model\System\Config\Yesno;
use DrinksAndCo\Blog\Model\Source\Category;

class Main extends Generic implements TabInterface
{
    protected $_wysiwygConfig;
    protected $_status;
    protected $_yesno;
    protected $_systemStore;
    protected $_category;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        Status $status,
        Yesno $yesno,
        \Magento\Store\Model\System\Store $systemStore,
        Category $category,
        array $data = []
    )
    {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_status = $status;
        $this->_yesno = $yesno;
        $this->_systemStore = $systemStore;
        $this->_category = $category;
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
        $model = $this->_coreRegistry->registry('current_post');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('post_general_');
        $fieldset = $form->addFieldset('general_fieldset', ['legend' => __('General')]);
        if ($model->getId()) {
            $fieldset->addField('post_id', 'hidden', ['name' => 'post_id']);
            $fieldset->addField('created_at', 'hidden', ['name' => 'post[created_at]']);
            $fieldset->addField('user', 'hidden', ['name' => 'post[user]']);
        }

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
            'title',
            'text',
            ['name' => 'post[title]', 'label' => __('Title'), 'title' => __('Title'), 'required' => true]
        );
        $fieldset->addField(
            'url_key',
            'text',
            ['name' => 'post[url_key]', 'label' => __('URL Key'), 'title' => __('URL Key'), 'required' => false, 'class' => 'validate-identifier']
        );

        /*$fieldContentType = $fieldset->addField(
            'content_type',
            'select',
            [
                'name' => 'post[content_type]',
                'label' => __('Content Type'),
                'title' => __('Content Type'),
                'required' => true,
                'style' => 'width: 30em;',
                'values' => [
                    ['value' => 'news', 'label'=>'News'],
                    ['value' => 'events', 'label'=>'Events'],
                ],
            ]
        );
        $renderer = $this->getLayout()->createBlock(
            'DrinksAndCo\Blog\Block\Adminhtml\ContentType'
        );
        $fieldContentType->setRenderer($renderer);*/

        $fieldset->addField(
            'category_id',
            'select',
            [
                'name' => 'post[category_id]',
                'label' => __('Category'),
                'title' => __('Category'),
                'required' => true,
                'style' => 'width: 30em;',
                'values' => $this->_category->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'thumbnail',
            'image',
            ['name' => 'thumbnail', 'label' => __('Thumbnail'),'note' => __('Show on Widget, Sidebar, List Post'), 'title' => __('Thumbnail'), 'required' => false]
        );
        $wysiwygConfig = $this->_wysiwygConfig->getConfig();
        $fieldset->addField(
            'content',
            'editor',
            ['name' => 'post[content]', 'label' => __('Content'), 'title' => __('Content'), 'required' => true, 'config' => $wysiwygConfig]
        );
        $fieldset->addField(
            'status',
            'select',
            ['name' => 'post[status]', 'label' => __('Status'), 'title' => __('Status'), 'options' => $this->_status->toOptionArray()]
        );
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
