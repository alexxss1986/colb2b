<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter(
        array(
            array('attribute'=> 'ca_brand','like' => 2352),
            array('attribute'=> 'ca_brand','like' => 1091),
            array('attribute'=> 'ca_brand','like' => 949),
            array('attribute'=> 'ca_brand','like' => 950),
        )
    )
    ->addAttributeToFilter("type_id","simple");



foreach ($collection as $product) {
    Mage::log($product->getId());
    $product2 = mage::getModel('catalog/product')->load($product->getId());
    $product2->setVisibility(1);
    $product2->save();

}

Mage::log("FINE",null,$logFileName);