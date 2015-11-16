<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id','configurable')
    ->addAttributeToFilter('ca_brand', array('in' => array(2589)));

Mage::log(count($collection));
foreach ($collection as $product) {
    Mage::log($product->getId());
    $product2 = Mage::getModel('catalog/product')->load($product->getId());
$product2->setVisibility(4);
    $product2->save();


}
Mage::log("FINE");

?>