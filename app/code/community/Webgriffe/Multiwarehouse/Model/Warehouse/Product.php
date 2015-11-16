<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 10/3/14
 * Time: 10:06 AM
 */ 
class Webgriffe_Multiwarehouse_Model_Warehouse_Product extends Mage_Core_Model_Abstract
{
    protected function _beforeSave()
    {
        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        if ($this->isObjectNew() && null === $this->getCreatedAt()) {
            $this->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }
        return parent::_beforeSave();
    }

    protected function _construct()
    {
        $this->_init('wgmulti/warehouse_product');
    }

}