<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('price', array('eq' => 0));


foreach ($collection as $product) {
    Mage::log($product->getId());
    $product2 = mage::getModel('catalog/product')->load($product->getId());
    $product2->setVisibility(1);
    $product2->save();

}
Mage::log("FINE");
?>