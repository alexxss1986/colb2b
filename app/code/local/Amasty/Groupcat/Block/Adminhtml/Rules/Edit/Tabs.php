<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
class Amasty_Groupcat_Block_Adminhtml_Rules_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('rulesTabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('amgroupcat')->__('Customer Group Catalog / Rules'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general', array(
            'label'     => Mage::helper('amgroupcat')->__('General Settings'),
            'content'   => $this->getLayout()->createBlock('amgroupcat/adminhtml_rules_edit_tab_general')
                ->setTitle('General Settings')
                ->toHtml(),
        ));

        $this->addTab('products', array(
            'label'     => Mage::helper('amgroupcat')->__('Products Access Restriction'),
            'title'     => Mage::helper('amgroupcat')->__('Products Access Restriction'),
            'class'     => 'ajax',
            'url'       => $this->getUrl('amgroupcat/adminhtml_products/products', array('_current' => true)),
        ));

        $this->addTab('categoryaccess', array(
            'label'     => Mage::helper('amgroupcat')->__('Category Access Restriction'),
            'content'   => $this->getLayout()->createBlock('amgroupcat/adminhtml_rules_edit_tab_categoryaccess')
                    ->setTitle('Category Access Restriction')
                    ->toHtml(),
        ));

        $this->addTab('restriction', array(
            'label'     => Mage::helper('amgroupcat')->__('Restriction Action'),
            'content'   => $this->getLayout()->createBlock('amgroupcat/adminhtml_rules_edit_tab_restriction')
                    ->setTitle('Restriction Action')
                    ->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}