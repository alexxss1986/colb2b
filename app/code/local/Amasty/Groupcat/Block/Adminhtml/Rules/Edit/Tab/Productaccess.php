<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
class Amasty_Groupcat_Block_Adminhtml_Rules_Edit_Tab_Productaccess extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('amgroupcatGridPr');
        $this->setUseAjax(true);
    }

    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
        if ($column->getId() == 'in_category') {
            $productIds = $this->getSavedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
            }
            elseif(!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')            
            ->addAttributeToSelect('price')
            ->addAttributeToFilter('visibility',array('neq' => 1));
            
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        $this->addColumn('in_category', array(
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'name'      => 'in_category',
            'values'    => $this->getSavedProducts(),
            'align'     => 'center',
            'index'     => 'entity_id'
        ));

        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('catalog')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'entity_id'
        ));
        
        $this->addColumn('name', array(
            'header'    => Mage::helper('catalog')->__('Name'),
            'index'     => 'name'
        ));
        
        $this->addColumn('sku', array(
            'header'    => Mage::helper('catalog')->__('SKU'),
            'width'     => '80',
            'index'     => 'sku'
        ));
        
        $this->addColumn('price', array(
            'header'    => Mage::helper('catalog')->__('Price'),
            'type'  => 'currency',
            'width'     => '1',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'price'
        ));
                
        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/products', array('_current' => true));
    }

    public function getRowUrl($row)
    {
       return $this->getUrl('adminhtml/catalog_product/edit', array('id' => $row->getEntityId()));
    }
    
    public function getSavedProducts()
    {
        return Mage::getModel('amgroupcat/product')->getProducts($this->_getRuleId());      
    }   
    
    protected function _getRuleId()
    {
        return $this->getRequest()->getParam('id', 0);   
    }
}
