<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id','configurable')
    ->addAttributeToFilter('entity_id', array('gt' => 14377));


foreach ($collection as $product) {
    $product2 = mage::getModel('catalog/product')->load($product->getId());
    if ($product2->getSmallImage() != null && $product2->getSmallImage() != "no_selection") {

    }
    else {
        Mage::log($product2->getId());
        $ids = $product2->getTypeInstance()->getUsedProductIds();
        // recupero tutti i prodotti semplici
        foreach ($ids as $id) {
            $productSimple = Mage::getModel('catalog/product')->load($id);
            $stockData = $productSimple->getStockData();
            $stockData['is_in_stock'] = 0;
            $productSimple->setStockData($stockData);
            $productSimple->save();

        }
        $stockData = $product2->getStockData();
        $stockData['is_in_stock'] = 0;
        $product2->setStockData($stockData);
        $product2->save();
    }

}
Mage::log("FINE");
?>