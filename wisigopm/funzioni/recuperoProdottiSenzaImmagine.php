<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id','configurable');

$filename="prodotti";
$logFileName= $filename.'.log';

foreach ($collection as $product) {
    $product2 = mage::getModel('catalog/product')->load($product->getId());
    $attributes = $product2->getTypeInstance(true)->getSetAttributes($product2);
    $gallery = $attributes['media_gallery'];
    $images = $product2->getMediaGalleryImages();
    if (count($images)==0){

    }
    else {
        if ($product2->getSmallImage() != null && $product2->getSmallImage() != "no_selection") {

        }
        else {
            Mage::log("SKU ".$product2->getSku(),null,$logFileName);
        }
    }
}
?>