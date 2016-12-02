<?php

namespace MGS\Blog\Block\Adminhtml\Post\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
use MGS\Blog\Model\System\Config\Status;
use MGS\Blog\Model\System\Config\Yesno;
use MGS\Blog\Model\Source\Category;

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
        $fieldset->addField(
            'categories',
            'multiselect',
            [
                'name' => 'categories[]',
                'label' => __('Categories'),
                'title' => __('Categories'),
                'required' => false,
                'style' => 'width: 30em;',
                'values' => $this->_category->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'thumbnail',
            'image',
            ['name' => 'thumbnail', 'label' => __('Thumbnail'), 'title' => __('Thumbnail'), 'required' => false]
        );
        $fieldset->addField(
            'image',
            'image',
            ['name' => 'image', 'label' => __('Image'), 'title' => __('Image'), 'required' => false]
        );
        $wysiwygConfig = $this->_wysiwygConfig->getConfig();
        $fieldset->addField(
            'short_content',
            'editor',
            ['name' => 'post[short_content]', 'label' => __('Short Content'), 'title' => __('Short Content'), 'required' => false, 'config' => $wysiwygConfig]
        );
        $fieldset->addField(
            'content',
            'editor',
            ['name' => 'post[content]', 'label' => __('Content'), 'title' => __('Content'), 'required' => true, 'config' => $wysiwygConfig]
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
            'tags',
            'textarea',
            ['name' => 'post[tags]', 'label' => __('Tags'), 'title' => __('Tags'), 'required' => false]
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
