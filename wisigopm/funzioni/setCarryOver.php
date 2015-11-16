<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('ca_brand', 1024);

$filename = "carryover";
$logFileName = $filename . '.log';

Mage::log("INIZIO",null,$logFileName);

foreach ($collection as $product) {
    Mage::log($product->getId(),null,$logFileName);
    $product2 = Mage::getModel('catalog/product')->load($product->getId());
    $product2->setCaCarryover(2503);
    $product2->getResource()->saveAttribute($product2, 'ca_carryover');
}

Mage::log("FINE",null,$logFileName);
?>