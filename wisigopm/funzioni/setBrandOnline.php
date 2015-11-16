<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id','configurable')
    ->addAttributeToFilter('ca_brand', array('in' => array(2622,2623)));


foreach ($collection as $product) {
    Mage::log($product->getId());
    $product2 = Mage::getModel('catalog/product')->load($product->getId());
    $product2->setVisibility(4);
    $product2->save();

    $product2Eng = Mage::getModel('catalog/product')->setStoreId(2)->load($product->getId());
    $product2Eng->setVisibility(4);
    $query = "delete from " . $resource->getTableName('am_groupcat_product') . " where product_id='" . $product->getId() . "'";
    $writeConnection->query($query);
    $product2Eng->save();

    $product2Usa= Mage::getModel('catalog/product')->setStoreId(3)->load($product->getId());
    $product2Usa->setVisibility(4);
    $product2Usa->save();

}
Mage::log("FINE");
?>