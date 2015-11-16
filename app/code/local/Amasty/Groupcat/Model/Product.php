<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */ 
class Amasty_Groupcat_Model_Product extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        
        $this->_init('amgroupcat/product');
    }
    
    public function getProducts($ruleId)
    {
        $ids = $this->getResource()->getProducts($ruleId);
        return $ids;    
    }

    
    public function assignProducts($productIds, $ruleId)
    {
        $this->getResource()->assignProducts($productIds, $ruleId);
        return $this;         
    }
}