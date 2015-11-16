<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */ 
class Amasty_Groupcat_Model_Mysql4_Product extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('amgroupcat/product', 'id');
    }

    /*
     * get products for rule from database
     */
    public function getProducts($ruleId)
    {
        $read = $this->_getReadAdapter();
        $tbl  = $this->getTable('amgroupcat/product');
        
        $select = $read->select()->from($tbl, 'product_id')->where('rule_id = ?', $ruleId);
        return $read->fetchCol($select);      
    } 

    /*
     * save products for rule in database
     */
    public function assignProducts($productIds, $rule_id)
    {
        $db = $this->_getWriteAdapter();

        $rule_id = intVal($rule_id);
        $db->delete($this->getTable('amgroupcat/product'), "rule_id=$rule_id"); 
        
        if (!$productIds)
            return false;
            
        $sql = 'INSERT INTO `' . $this->getTable('amgroupcat/product') . '` (`rule_id`, `product_id`) VALUES ';
        foreach ($productIds as $id) {
            $id  = intVal($id);
            $sql .= "($rule_id , $id),";
        }
        $db->raw_query(substr($sql, 0, -1));
        
        return true;
    }                     
}