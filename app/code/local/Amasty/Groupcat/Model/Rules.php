<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
class Amasty_Groupcat_Model_Rules extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('amgroupcat/rules');
    }

    public function getActiveRules($group,$params=false)
    {
        return $this->_getResource()->getActiveRules($group, $params);
    }

    public function getActiveRulesForProduct($productId,$group,$params=false)
    {
        return $this->_getResource()->getActiveRulesForProduct($productId,$group, $params);
    }

    public function getActiveRulesForProductPrice($productId,$group,$params=false)
    {
        return $this->_getResource()->getActiveRulesForProductPrice($productId,$group, $params);
    }
}