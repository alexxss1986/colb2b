<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */

/**
 * Created by PhpStorm.
 * User: grinkevich
 * Date: 21.08.14
 * Time: 12:48
 */

class Amasty_Groupcat_Model_Mysql4_Rules extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('amgroupcat/rules', 'rule_id');
    }


    public function getActiveRules($group, $params=false)
    {
        $storeId = Mage::app()->getStore()->getId();
        $read = $this->_getReadAdapter();

        $select = $read->select()
            ->from($this->getMainTable())
            ->where('enable = 1')
            ->where('cust_groups LIKE ?', '%,' . $group . ',%')
            ->where('( (stores LIKE ?) OR (stores LIKE "%,0,%") )', '%,' . $storeId . ',%');

        if (is_array($params)){
            $where = '( '. implode(' ) AND ( ',$params). ' )';
            $select->where($where);
        }

        return $read->fetchAll($select);
    }

    public function getActiveRulesForProduct($productId,$group, $params=false)
    {
        $storeId = Mage::app()->getStore()->getId();
        $read = $this->_getReadAdapter();
        $prodTable = $this->getTable('amgroupcat/product');
        $ruleTable = $this->getTable('amgroupcat/rules');

        $select = $read->select()
            ->from(array('p'=>$prodTable))
            ->join(array('r'=>$ruleTable),'p.rule_id = r.rule_id')
            ->where('r.enable = 1')
            ->where('r.cust_groups LIKE ?', '%,' . $group . ',%')
            ->where('( (r.stores LIKE ?) OR (r.stores LIKE "%,0,%") )', '%,' . $storeId . ',%')
            ->where('p.product_id = ?',$productId);

        if (is_array($params)){
            $where = '( '. implode(' ) AND ( ',$params). ' )';
            $select->where($where);
        }

        return $read->fetchAll($select);
    }

    public function getActiveRulesForProductPrice($productId,$group, $params=false)
    {
        $storeId = Mage::app()->getStore()->getId();
        $read = $this->_getReadAdapter();
        $prodTable = $this->getTable('amgroupcat/product');
        $ruleTable = $this->getTable('amgroupcat/rules');

        $select = $read->select()
            ->from(array('p'=>$prodTable))
            ->join(array('r'=>$ruleTable),'p.rule_id = r.rule_id')
            ->where('r.enable = 1')
            ->where('r.cust_groups LIKE ?', '%,' . $group . ',%')
            ->where('( (r.stores LIKE ?) OR (r.stores LIKE "%,0,%") )', '%,' . $storeId . ',%')
            ->where('r.hide_price = 1')
            ->where('p.product_id = ?',$productId);

        if (is_array($params)){
            $where = '( '. implode(' ) AND ( ',$params). ' )';
            $select->where($where);
        }

        return $read->fetchAll($select);
    }

}