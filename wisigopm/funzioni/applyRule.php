<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



//$prodotto=Mage::getModel("catalog/product")->load(353);

Mage::getResourceModel('catalogrule/rule')->applyAllRules();
//$rule=Mage::getModel('catalogrule/rule')->applyAllRulesToProduct($prodotto);
/*$rules = Mage::getModel('catalogrule/rule')->getCollection()
    ->addFieldToFilter('is_active', 1)
    ->setOrder('rule_id','ASC');
foreach ($rules as $rule) {
    echo $rule->getId()."<br>";
}*/


