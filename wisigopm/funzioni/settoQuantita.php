<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter("entity_id", array('gt' => 1091));

$filename="aggiornaDisponibilita";
$logFileName= $filename.'.log';


foreach ($collection as $product) {
    Mage::log($product->getId());
    $product2 = mage::getModel('catalog/product')->load($product->getId());
    $stockData = $product2->getStockData();
    $stockData['qty'] = 0;
    $stockData['is_in_stock'] = 0;
    $product2->setStockData($stockData);
    $product2->save();

}

Mage::log("FINE",null,$logFileName);