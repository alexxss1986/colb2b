<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
class Amasty_Groupcat_Block_Adminhtml_Rules_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('rulesGrid');
        $this->setDefaultSort('rule_id');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('amgroupcat/rules')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $hlp =  Mage::helper('amgroupcat');
        $this->addColumn('rule_name', array(
            'header'    => $hlp->__('Rule Name'),
            'align'     => 'center',
            'index'     => 'rule_name',
        ));

        $this->addColumn('cust_groups', array(
            'header'    => $hlp->__('Affected Customer Groups'),
            'align'     => 'center',
            'index'     => 'cust_groups',
            'type'      => 'options',
            'options'   => Mage::getResourceModel('customer/group_collection')->load()->toOptionHash(),
            'renderer'  => 'amgroupcat/adminhtml_rules_grid_renderer_custGroups',
            'filter'    => 'amgroupcat/adminhtml_rules_grid_filter_custGroups',
        ));

        $this->addColumn('cats_count', array(
            'header'    => $hlp->__('Number Of Affected Categories'),
            'align'     => 'center',
            'index'     => 'cats_count',
        ));

        $this->addColumn('prods_count', array(
            'header'    => $hlp->__('Number Of Affected Products'),
            'align'     => 'center',
            'index'     => 'prods_count',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('stores', array(
                'header'        => Mage::helper('cms')->__('Store View'),
                'index'         => 'stores',
                'type'          => 'stores',
                'sortable'      => false,
                'renderer'      => 'amgroupcat/adminhtml_rules_grid_renderer_stores',
                'filter'        => 'amgroupcat/adminhtml_rules_grid_filter_stores',
            ));
        }

        $this->addColumn('enable', array(
            'header'    => $hlp->__('Enabled'),
            'align'     => 'center',
            'width'     => '80px',
            'index'     => 'enable',
            'type'      => 'options',
            'options'   => array(
                '0' => $this->__('No'),
                '1' => $this->__('Yes'),
            ),
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('rule_id');
        $this->getMassactionBlock()->setFormFieldName('ids');

        $actions = array(
            'massDelete'     => 'Delete',
        );
        foreach ($actions as $code => $label){
            $this->getMassactionBlock()->addItem($code, array(
                'label'    => Mage::helper('amgroupcat')->__($label),
                'url'      => $this->getUrl('*/*/' . $code),
                'confirm'  => ($code == 'massDelete' ? Mage::helper('amgroupcat')->__('Are you sure?') : null),
            ));
        }
        return $this;
    }
}