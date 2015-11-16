<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$productSimple = Mage::getModel('catalog/product')->load(49798);
echo $productSimple->getStockItem()->getQty();