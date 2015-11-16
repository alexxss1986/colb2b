<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

Mage::log("INIZIO");

$rule = Mage::getModel('catalogrule/rule')->load(19);  /* catalog price rule id */
$rule->setWebsiteIds("3");
$productIdsArray = $rule->getMatchingProductIds();

$productsCollection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect("entity_id")
    ->addAttributeToFilter("entity_id", array("in", $productIdsArray));

Mage::log($productsCollection);