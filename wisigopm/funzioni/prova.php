<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$product = Mage::getModel('catalog/product')->load(75977);

$nome_coloreEng = $product->getResource()->getAttribute('ca_colore')->setStoreId(2)->getFrontend()->getValue($product);

echo $nome_coloreEng."<br>";


?>