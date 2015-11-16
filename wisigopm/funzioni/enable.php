<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$filename = "prodotti";
$logFileName = $filename . '.log';

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('ca_brand',993);

foreach ($collection as $product){
    Mage::log($product->getId(),null,$logFileName);
    $product2=Mage::getModel("catalog/product")->setStoreId(2)->load($product->getId());
    $product2->setStatus(1);
    $product2->save();
}

Mage::log("FINE",null,$logFileName);